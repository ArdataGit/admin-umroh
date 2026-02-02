<?php

namespace App\Http\Controllers;

use App\Models\Jamaah;
use App\Models\Agent;
use App\Models\KeberangkatanHaji;
use App\Models\CustomerHaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranHajiController extends Controller
{
    public function index()
    {
        $pendaftarans = CustomerHaji::with(['jamaah', 'keberangkatanHaji', 'agent'])
            ->latest()
            ->get();

        return view('pages.pendaftaran-haji.index', [
            'title' => 'Data Pendaftaran Haji',
            'pendaftarans' => $pendaftarans
        ]);
    }

    public function create()
    {
        // Generate Auto Code J-XXX (Shared with Umroh/Jamaah Table)
        $lastJamaah = Jamaah::latest('id')->first();
        $nextId = $lastJamaah ? ($lastJamaah->id + 1) : 1;
        $kodeJamaah = 'J-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $agents = Agent::all();
        // Filter only Active Haji Departures
        $keberangkatans = KeberangkatanHaji::with('paketHaji')->where('status_keberangkatan', 'active')->get();

        return view('pages.pendaftaran-haji.create', [
            'title' => 'Pendaftaran Haji',
            'kodeJamaah' => $kodeJamaah,
            'agents' => $agents,
            'keberangkatans' => $keberangkatans
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Section 1: Jamaah Data
            'kode_jamaah' => 'required|unique:jamaahs,kode_jamaah',
            'nik_jamaah' => 'required|numeric|unique:jamaahs,nik_jamaah',
            'nama_jamaah' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'kontak_jamaah' => 'required|string',
            'email_jamaah' => 'nullable|email',
            'kecamatan' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'alamat_jamaah' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'catatan_jamaah' => 'nullable|string',
            
            // Passport
            'nama_paspor' => 'nullable|string',
            'nomor_paspor' => 'nullable|string',
            'kantor_imigrasi' => 'nullable|string',
            'tgl_paspor_aktif' => 'nullable|date',
            'tgl_paspor_expired' => 'nullable|date',

            // Files
            'foto_jamaah' => 'nullable|image|max:2048',
            'foto_ktp' => 'nullable|image|max:2048',
            'foto_kk' => 'nullable|image|max:2048',
            'foto_paspor_1' => 'nullable|image|max:2048',
            'foto_paspor_2' => 'nullable|image|max:2048',

            // Section 2: Manifest Data (Haji)
            'keberangkatan_haji_id' => 'required|exists:keberangkatan_hajis,id',
            'agent_id' => 'required|exists:agents,id',
            'tipe_kamar' => 'required|in:quad,triple,double',
            'jumlah_jamaah' => 'required|integer|min:1',
            'nama_keluarga' => 'nullable|string',
            'harga_paket' => 'required|numeric',
            'diskon' => 'required|numeric',
            'total_bayar' => 'required|numeric',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'catatan_pendaftaran' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // 1. Handle File Uploads
            $paths = [];
            foreach (['foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_paspor_1', 'foto_paspor_2'] as $fileKey) {
                if ($request->hasFile($fileKey)) {
                    $paths[$fileKey] = $request->file($fileKey)->store('jamaah_docs/' . $validated['nik_jamaah'], 'public');
                } else {
                    $paths[$fileKey] = null;
                }
            }

            // 2. Create Jamaah
            $jamaah = Jamaah::create([
                'kode_jamaah' => $validated['kode_jamaah'],
                'nik_jamaah' => $validated['nik_jamaah'],
                'nama_jamaah' => $validated['nama_jamaah'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'kontak_jamaah' => $validated['kontak_jamaah'],
                'email_jamaah' => $validated['email_jamaah'],
                'kecamatan' => $validated['kecamatan'],
                'kabupaten_kota' => $validated['kabupaten_kota'],
                'provinsi' => $validated['provinsi'],
                'alamat_jamaah' => $validated['alamat_jamaah'],
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'catatan_jamaah' => $validated['catatan_jamaah'],
                'nama_paspor' => $validated['nama_paspor'],
                'nomor_paspor' => $validated['nomor_paspor'],
                'kantor_imigrasi' => $validated['kantor_imigrasi'],
                'tgl_paspor_aktif' => $validated['tgl_paspor_aktif'],
                'tgl_paspor_expired' => $validated['tgl_paspor_expired'],
                'foto_jamaah' => $paths['foto_jamaah'],
                'foto_ktp' => $paths['foto_ktp'],
                'foto_kk' => $paths['foto_kk'],
                'foto_paspor_1' => $paths['foto_paspor_1'],
                'foto_paspor_2' => $paths['foto_paspor_2'],
            ]);

            // 3. Create CustomerHaji (Manifest)
            $totalTagihan = ($validated['harga_paket'] * $validated['jumlah_jamaah']) - $validated['diskon'];
            $sisaTagihan = $totalTagihan - $validated['total_bayar'];

            $customerHaji = CustomerHaji::create([
                'keberangkatan_haji_id' => $validated['keberangkatan_haji_id'],
                'jamaah_id' => $jamaah->id,
                'agent_id' => $validated['agent_id'],
                'tipe_kamar' => $validated['tipe_kamar'],
                'jumlah_jamaah' => $validated['jumlah_jamaah'],
                'nama_keluarga' => $validated['nama_keluarga'],
                'harga_paket' => $validated['harga_paket'],
                'diskon' => $validated['diskon'],
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $validated['total_bayar'],
                'sisa_tagihan' => $sisaTagihan,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_visa' => $request->has('status_visa'),
                'status_tiket' => $request->has('status_tiket'),
                'status_siskopatuh' => $request->has('status_siskopatuh'),
                'status_perlengkapan' => $request->has('status_perlengkapan'),
                'catatan' => $validated['catatan_pendaftaran']
            ]);

            // Auto-create PembayaranHaji Record if total_bayar > 0
            if ($validated['total_bayar'] > 0) {
                // Create temporary record to get ID
                $pembayaran = \App\Models\PembayaranHaji::create([
                    'customer_haji_id' => $customerHaji->id,
                    'kode_transaksi' => 'TEMP-' . uniqid(),
                    'jumlah_pembayaran' => $validated['total_bayar'],
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'checked',
                    'tanggal_pembayaran' => now(),
                    'catatan' => 'Pembayaran awal pendaftaran'
                ]);

                // Update with correct Code Format: INV/H/{kode jamaah}/{kode keberangkatan}/{id}
                $keberangkatan = \App\Models\KeberangkatanHaji::find($validated['keberangkatan_haji_id']);
                
                $kodeTransaksi = "INV/H/{$validated['kode_jamaah']}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";
                
                $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Haji Berhasil Disimpan',
                'redirect' => route('customer-haji.index', $validated['keberangkatan_haji_id'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Delete uploaded files if failed
            foreach ($paths as $path) {
                if ($path) Storage::disk('public')->delete($path);
            }
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }
}
