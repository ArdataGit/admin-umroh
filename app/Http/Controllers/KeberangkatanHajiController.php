<?php

namespace App\Http\Controllers;

use App\Models\KeberangkatanHaji;
use App\Models\PaketHaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class KeberangkatanHajiController extends Controller
{
    public function index()
    {
        $keberangkatan = KeberangkatanHaji::with(['paketHaji.maskapai'])->latest()->get();
        return view('pages.keberangkatan-haji.index', [
            'title' => 'Keberangkatan Haji',
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function create()
    {
        $lastKeberangkatan = KeberangkatanHaji::latest()->first();
        $nextId = $lastKeberangkatan ? ($lastKeberangkatan->id + 1) : 1;
        // Format KH-XXX
        $kodeKeberangkatan = 'KH-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        return view('pages.keberangkatan-haji.create', [
            'title' => 'Tambah Keberangkatan Haji',
            'paketHajis' => PaketHaji::all(),
            'kodeKeberangkatan' => $kodeKeberangkatan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_keberangkatan' => 'required|unique:keberangkatan_hajis,kode_keberangkatan',
            'paket_haji_id' => 'required|exists:paket_hajis,id',
            'status_keberangkatan' => 'required|in:active,completed',
            'catatan' => 'nullable|string'
        ]);

        $paket = PaketHaji::findOrFail($validated['paket_haji_id']);

        try {
            KeberangkatanHaji::create([
                'kode_keberangkatan' => $validated['kode_keberangkatan'],
                'paket_haji_id' => $validated['paket_haji_id'],
                'nama_keberangkatan' => $paket->nama_paket,
                'tanggal_keberangkatan' => $paket->tanggal_keberangkatan,
                'jumlah_hari' => $paket->jumlah_hari,
                'kuota_jamaah' => $paket->kuota_jamaah,
                'status_keberangkatan' => $validated['status_keberangkatan'],
                'catatan' => $validated['catatan']
            ]);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Haji',
                'action' => 'Create',
                'keterangan' => 'Menambah keberangkatan haji baru: ' . $paket->nama_paket . ' (' . $validated['kode_keberangkatan'] . ')'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Keberangkatan Haji berhasil disimpan',
                'redirect' => route('keberangkatan-haji.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $keberangkatan = KeberangkatanHaji::with('paketHaji')->findOrFail($id);
        return view('pages.keberangkatan-haji.show', [
            'title' => 'Detail Keberangkatan Haji',
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function edit($id)
    {
        $keberangkatan = KeberangkatanHaji::findOrFail($id);
        return view('pages.keberangkatan-haji.edit', [
            'title' => 'Edit Keberangkatan Haji',
            'keberangkatan' => $keberangkatan,
            'paketHajis' => PaketHaji::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'paket_haji_id' => 'required|exists:paket_hajis,id',
            'status_keberangkatan' => 'required|in:active,completed',
            'catatan' => 'nullable|string'
        ]);

        $paket = PaketHaji::findOrFail($validated['paket_haji_id']);

        try {
            $keberangkatan = KeberangkatanHaji::findOrFail($id);
            $keberangkatan->update([
                'paket_haji_id' => $validated['paket_haji_id'],
                'nama_keberangkatan' => $paket->nama_paket,
                'tanggal_keberangkatan' => $paket->tanggal_keberangkatan,
                'jumlah_hari' => $paket->jumlah_hari,
                'kuota_jamaah' => $paket->kuota_jamaah,
                'status_keberangkatan' => $validated['status_keberangkatan'],
                'catatan' => $validated['catatan']
            ]);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Haji',
                'action' => 'Update',
                'keterangan' => 'Memperbarui keberangkatan haji: ' . $paket->nama_paket . ' (' . $keberangkatan->kode_keberangkatan . ')'
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Keberangkatan Haji berhasil diperbarui',
                'redirect' => route('keberangkatan-haji.index')
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $keberangkatan = KeberangkatanHaji::findOrFail($id);
            $namaKeberangkatan = $keberangkatan->nama_keberangkatan;
            $kodeKeberangkatan = $keberangkatan->kode_keberangkatan;

            $keberangkatan->delete();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Keberangkatan Haji',
                'action' => 'Delete',
                'keterangan' => 'Menghapus keberangkatan haji: ' . $namaKeberangkatan . ' (' . $kodeKeberangkatan . ')'
            ]);

            return response()->json(['success' => true, 'message' => 'Keberangkatan Haji berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus: ' . $e->getMessage()], 500);
        }
    }
}
