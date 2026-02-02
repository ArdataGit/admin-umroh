<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabunganUmroh;
use Carbon\Carbon;

class SetoranUmrohController extends Controller
{
    public function generalIndex()
    {
        $transaksis = \App\Models\TransaksiTabunganUmroh::with(['tabunganUmroh.jamaah'])
            ->where('jenis_transaksi', 'setoran')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('pages.setoran-umroh.general-index', [
            'title' => 'Data Setoran Umroh',
            'transaksis' => $transaksis
        ]);
    }

    public function index($id)
    {
        $tabungan = TabunganUmroh::with(['jamaah', 'transaksiTabunganUmrohs' => function($query) {
            $query->orderBy('tanggal_transaksi', 'desc')->orderBy('id', 'desc');
        }])->findOrFail($id);

        $umur = Carbon::parse($tabungan->jamaah->tanggal_lahir)->age;
        $total_penarikan = $tabungan->transaksiTabunganUmrohs()->where('jenis_transaksi', 'penarikan')->sum('nominal');

        return view('pages.setoran-umroh.index', [
            'title' => 'Setoran Umroh',
            'tabungan' => $tabungan,
            'umur' => $umur,
            'total_penarikan' => $total_penarikan
        ]);
    }

    public function create($id)
    {
        $tabungan = TabunganUmroh::with('jamaah')->findOrFail($id);
        
        // Generate Transaction Code
        // Format: INV/CR/{Kode Jamaah}/{Kode Tabungan}/{ID}
        // Since we don't have the ID yet, we'll use the next available ID from the transactions table
        $lastTransaction = \App\Models\TransaksiTabunganUmroh::orderBy('id', 'desc')->first();
        $nextId = $lastTransaction ? $lastTransaction->id + 1 : 1;
        
        $kodeTransaksi = "INV/CR/{$tabungan->jamaah->kode_jamaah}/{$tabungan->kode_tabungan}/{$nextId}";

        return view('pages.setoran-umroh.create', [
            'title' => 'Tambah Setoran Umroh',
            'tabungan' => $tabungan,
            'kodeTransaksi' => $kodeTransaksi
        ]);
    }

    public function store(Request $request, $id)
    {
        $tabungan = TabunganUmroh::findOrFail($id);

        $validated = $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi_tabungan_umrohs,kode_transaksi',
            'nominal' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'tanggal_transaksi' => 'required|date',
            'kode_referensi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|max:2048'
        ]);

        $data = [
            'tabungan_umroh_id' => $tabungan->id,
            'kode_transaksi' => $validated['kode_transaksi'],
            'jenis_transaksi' => 'setoran',
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'kode_referensi' => $validated['kode_referensi'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_transaksi')) {
            $path = $request->file('bukti_transaksi')->store('bukti-transaksi', 'public');
            $data['bukti_transaksi'] = $path;
        }

        \App\Models\TransaksiTabunganUmroh::create($data);

        // Update Tabungan Balance
        $tabungan->increment('setoran_tabungan', $validated['nominal']);

        return redirect()->route('setoran-umroh.index', $id)->with('success', 'Setoran berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $transaksi = \App\Models\TransaksiTabunganUmroh::findOrFail($id);
        $tabungan = $transaksi->tabunganUmroh;

        // Revert Balance
        // If it was a 'setoran', we decrement. If 'penarikan', we increment.
        if ($transaksi->jenis_transaksi == 'setoran') {
            $tabungan->decrement('setoran_tabungan', $transaksi->nominal);
        } elseif ($transaksi->jenis_transaksi == 'penarikan') {
            $tabungan->increment('setoran_tabungan', $transaksi->nominal);
        }

        // Delete valid proof of transaction if exists
        if ($transaksi->bukti_transaksi && \Illuminate\Support\Facades\Storage::exists('public/' . $transaksi->bukti_transaksi)) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $transaksi->bukti_transaksi);
        }

        $transaksi->delete();

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
    }

    public function edit($id)
    {
        $transaksi = \App\Models\TransaksiTabunganUmroh::with(['tabunganUmroh.jamaah'])->findOrFail($id);
        $tabungan = $transaksi->tabunganUmroh;

        return view('pages.setoran-umroh.edit', [
            'title' => 'Edit Setoran Umroh',
            'tabungan' => $tabungan,
            'transaksi' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi = \App\Models\TransaksiTabunganUmroh::findOrFail($id);
        $tabungan = $transaksi->tabunganUmroh;

        $validated = $request->validate([
            'nominal' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'status_setoran' => 'required|in:checked,completed',
            'tanggal_transaksi' => 'required|date',
            'kode_referensi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|max:2048'
        ]);

        // Calculate Balance Difference
        $oldNominal = $transaksi->nominal;
        $newNominal = $validated['nominal'];
        $diff = $newNominal - $oldNominal;

        $data = [
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_setoran' => $validated['status_setoran'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'kode_referensi' => $validated['kode_referensi'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_transaksi')) {
            // Delete old proof
            if ($transaksi->bukti_transaksi && \Illuminate\Support\Facades\Storage::exists('public/' . $transaksi->bukti_transaksi)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $transaksi->bukti_transaksi);
            }
            $path = $request->file('bukti_transaksi')->store('bukti-transaksi', 'public');
            $data['bukti_transaksi'] = $path;
        }

        $transaksi->update($data);

        // Update Tabungan Balance based on diff
        if ($transaksi->jenis_transaksi == 'setoran') {
            if ($diff > 0) {
                $tabungan->increment('setoran_tabungan', $diff);
            } elseif ($diff < 0) {
                $tabungan->decrement('setoran_tabungan', abs($diff));
            }
        } elseif ($transaksi->jenis_transaksi == 'penarikan') {
            // For penarikan, increasing nominal means decreasing balance
            if ($diff > 0) {
                $tabungan->decrement('setoran_tabungan', $diff);
            } elseif ($diff < 0) {
                $tabungan->increment('setoran_tabungan', abs($diff));
            }
        }

        return redirect()->route('setoran-umroh.index', $tabungan->id)->with('success', 'Transaksi berhasil diperbarui');
    }
}
