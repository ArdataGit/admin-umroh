<?php

namespace App\Http\Controllers;

use App\Models\TabunganHaji;
use App\Models\TransaksiTabunganHaji;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class SetoranHajiController extends Controller
{
    public function generalIndex()
    {
        $transaksis = \App\Models\TransaksiTabunganHaji::with(['tabunganHaji.jamaah'])
            ->where('jenis_transaksi', 'setoran')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        return view('pages.setoran-haji.general-index', [
            'title' => 'Data Setoran Haji',
            'transaksis' => $transaksis
        ]);
    }

    public function index($id)
    {
        $tabungan = TabunganHaji::with(['jamaah', 'transaksiTabunganHajis' => function($query) {
            $query->orderBy('tanggal_transaksi', 'desc')->orderBy('id', 'desc');
        }])->findOrFail($id);

        $umur = Carbon::parse($tabungan->jamaah->tanggal_lahir)->age;
        $total_penarikan = $tabungan->transaksiTabunganHajis()->where('jenis_transaksi', 'penarikan')->sum('nominal');

        return view('pages.setoran-haji.index', [
            'title' => 'Setoran Haji',
            'tabungan' => $tabungan,
            'umur' => $umur,
            'total_penarikan' => $total_penarikan
        ]);
    }

    public function create($id)
    {
        $tabungan = TabunganHaji::with('jamaah')->findOrFail($id);
        
        // Generate Transaction Code
        // Format: INV/CR/{Kode Jamaah}/{Kode Tabungan}/{ID}
        $lastTransaction = TransaksiTabunganHaji::orderBy('id', 'desc')->first();
        $nextId = $lastTransaction ? $lastTransaction->id + 1 : 1;
        
        $kodeTransaksi = "INV/CR/{$tabungan->jamaah->kode_jamaah}/{$tabungan->kode_tabungan}/{$nextId}";

        return view('pages.setoran-haji.create', [
            'title' => 'Tambah Setoran Haji',
            'tabungan' => $tabungan,
            'kodeTransaksi' => $kodeTransaksi
        ]);
    }

    public function store(Request $request, $id)
    {
        $tabungan = TabunganHaji::findOrFail($id);

        $validated = $request->validate([
            'kode_transaksi' => 'required|string|unique:transaksi_tabungan_hajis,kode_transaksi',
            'nominal' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'tanggal_transaksi' => 'required|date',
            'kode_referensi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image'
        ]);

        $data = [
            'tabungan_haji_id' => $tabungan->id,
            'kode_transaksi' => $validated['kode_transaksi'],
            'jenis_transaksi' => 'setoran',
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_setoran' => 'checked',
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'kode_referensi' => $validated['kode_referensi'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_transaksi')) {
            $path = $request->file('bukti_transaksi')->store('bukti-transaksi-haji', 'public');
            $data['bukti_transaksi'] = $path;
        }

        TransaksiTabunganHaji::create($data);

        // Update Tabungan Balance
        $tabungan->increment('setoran_tabungan', $validated['nominal']);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Setoran Haji',
            'action' => 'Create',
            'keterangan' => 'Menambahkan transaksi ' . $data['jenis_transaksi'] . ' haji: ' . $validated['kode_transaksi'] . ' untuk jamaah: ' . ($tabungan->jamaah->nama_jamaah ?? 'N/A') . ' sebesar ' . number_format($validated['nominal'], 0, ',', '.')
        ]);

        return redirect()->route('setoran-haji.index', $id)->with('success', 'Setoran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $transaksi = TransaksiTabunganHaji::with(['tabunganHaji.jamaah'])->findOrFail($id);
        $tabungan = $transaksi->tabunganHaji;

        return view('pages.setoran-haji.edit', [
            'title' => 'Edit Setoran Haji',
            'tabungan' => $tabungan,
            'transaksi' => $transaksi
        ]);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiTabunganHaji::findOrFail($id);
        $tabungan = $transaksi->tabunganHaji;

        $validated = $request->validate([
            'nominal' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:Cash,Transfer,Debit,QRIS,Other',
            'status_setoran' => 'required|in:checked,completed',
            'tanggal_transaksi' => 'required|date',
            'kode_referensi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image'
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
            $path = $request->file('bukti_transaksi')->store('bukti-transaksi-haji', 'public');
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
            if ($diff > 0) {
                $tabungan->decrement('setoran_tabungan', $diff);
            } elseif ($diff < 0) {
                $tabungan->increment('setoran_tabungan', abs($diff));
            }
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Setoran Haji',
            'action' => 'Update',
            'keterangan' => 'Memperbarui transaksi ' . $transaksi->jenis_transaksi . ' haji: ' . $transaksi->kode_transaksi . ' (Nominal baru: ' . number_format($validated['nominal'], 0, ',', '.') . ')'
        ]);

        return redirect()->route('setoran-haji.index', $tabungan->id)->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiTabunganHaji::findOrFail($id);
        $tabungan = $transaksi->tabunganHaji;

        // Revert Balance
        if ($transaksi->jenis_transaksi == 'setoran') {
            $tabungan->decrement('setoran_tabungan', $transaksi->nominal);
        } elseif ($transaksi->jenis_transaksi == 'penarikan') {
            $tabungan->increment('setoran_tabungan', $transaksi->nominal);
        }

        if ($transaksi->bukti_transaksi && \Illuminate\Support\Facades\Storage::exists('public/' . $transaksi->bukti_transaksi)) {
            \Illuminate\Support\Facades\Storage::delete('public/' . $transaksi->bukti_transaksi);
        }

        $kodeTransaksi = $transaksi->kode_transaksi;
        $jenisTransaksi = $transaksi->jenis_transaksi;

        $transaksi->delete();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Setoran Haji',
            'action' => 'Delete',
            'keterangan' => 'Menghapus transaksi ' . $jenisTransaksi . ' haji: ' . $kodeTransaksi
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
    }
}
