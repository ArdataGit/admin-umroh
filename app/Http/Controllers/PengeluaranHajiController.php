<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranHaji;
use App\Models\KeberangkatanHaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CodeGenerator;

class PengeluaranHajiController extends Controller
{
    public function index()
    {
        $pengeluaran = PengeluaranHaji::with('keberangkatanHaji')->latest()->get();
        return view('pages.pengeluaran-haji.index', [
            'title' => 'Data Pengeluaran Haji',
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function create()
    {
        $keberangkatans = KeberangkatanHaji::with('paketHaji')->where('status_keberangkatan', 'active')->get();
        $kodePengeluaran = CodeGenerator::generate(PengeluaranHaji::class, 'kode_pengeluaran', 'CH-', 6);

        return view('pages.pengeluaran-haji.create', [
            'title' => 'Tambah Pengeluaran Haji',
            'keberangkatans' => $keberangkatans,
            'kodePengeluaran' => $kodePengeluaran
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keberangkatan_haji_id' => 'required|exists:keberangkatan_hajis,id',
            'kode_pengeluaran' => 'required|unique:pengeluaran_hajis,kode_pengeluaran',
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

        PengeluaranHaji::create([
            'keberangkatan_haji_id' => $validated['keberangkatan_haji_id'],
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
            'menu' => 'Pengeluaran Haji',
            'action' => 'Create',
            'keterangan' => 'Menambah pengeluaran haji baru: ' . $validated['nama_pengeluaran'] . ' (' . $validated['kode_pengeluaran'] . ')'
        ]);

        return redirect()->route('pengeluaran-haji.index')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
