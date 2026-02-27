<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class PengeluaranUmumController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranUmum::latest()->get();
        return view('pages.pengeluaran-umum.index', [
            'title' => 'Data Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function create()
    {
        // Generate Auto Code CG-XXX
        $count = PengeluaranUmum::count() + 1;
        $kodePengeluaran = 'CG-' . str_pad($count, 6, '0', STR_PAD_LEFT);

        return view('pages.pengeluaran-umum.create', [
            'title' => 'Tambah Pengeluaran Umum',
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_pengeluaran' => 'required|unique:pengeluaran_umums,kode_pengeluaran',
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image'
        ]);

        $path = null;
        if ($request->hasFile('bukti_pengeluaran')) {
            $path = $request->file('bukti_pengeluaran')->store('bukti_pengeluaran', 'public');
        }

        PengeluaranUmum::create([
            'kode_pengeluaran' => $validated['kode_pengeluaran'],
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pengeluaran Umum',
            'action' => 'Create',
            'keterangan' => 'Menambah pengeluaran umum baru: ' . $validated['nama_pengeluaran'] . ' (' . $validated['kode_pengeluaran'] . ')'
        ]);

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
    public function show($id)
    {
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        return view('pages.pengeluaran-umum.show', [
            'title' => 'Detail Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function edit($id)
    {
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        return view('pages.pengeluaran-umum.edit', [
            'title' => 'Edit Pengeluaran Umum',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = PengeluaranUmum::findOrFail($id);

        $validated = $request->validate([
            'tanggal_pengeluaran' => 'required|date',
            'jenis_pengeluaran' => 'required|string',
            'nama_pengeluaran' => 'required|string',
            'jumlah_pengeluaran' => 'required|numeric',
            'catatan_pengeluaran' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image'
        ]);

        $path = $pengeluaran->bukti_pengeluaran;
        if ($request->hasFile('bukti_pengeluaran')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('bukti_pengeluaran')->store('bukti_pengeluaran', 'public');
        }

        $pengeluaran->update([
            'tanggal_pengeluaran' => $validated['tanggal_pengeluaran'],
            'jenis_pengeluaran' => $validated['jenis_pengeluaran'],
            'nama_pengeluaran' => $validated['nama_pengeluaran'],
            'jumlah_pengeluaran' => $validated['jumlah_pengeluaran'],
            'catatan_pengeluaran' => $validated['catatan_pengeluaran'],
            'bukti_pengeluaran' => $path
        ]);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pengeluaran Umum',
            'action' => 'Update',
            'keterangan' => 'Memperbarui pengeluaran umum: ' . $pengeluaran->nama_pengeluaran . ' (' . $pengeluaran->kode_pengeluaran . ')'
        ]);

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengeluaran = PengeluaranUmum::findOrFail($id);
        
        $namaPengeluaran = $pengeluaran->nama_pengeluaran;
        $kodePengeluaran = $pengeluaran->kode_pengeluaran;

        if ($pengeluaran->bukti_pengeluaran && Storage::disk('public')->exists($pengeluaran->bukti_pengeluaran)) {
            Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
        }

        $pengeluaran->delete();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Pengeluaran Umum',
            'action' => 'Delete',
            'keterangan' => 'Menghapus pengeluaran umum: ' . $namaPengeluaran . ' (' . $kodePengeluaran . ')'
        ]);

        return redirect()->route('pengeluaran-umum.index')->with('success', 'Pengeluaran berhasil dihapus');
    }
}
