<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaskapaiService;
use Illuminate\Support\Facades\Storage;

class MaskapaiController extends Controller
{
    protected $maskapaiService;

    public function __construct(MaskapaiService $maskapaiService)
    {
        $this->maskapaiService = $maskapaiService;
    }

    public function index()
    {
        $dataMaskapai = $this->maskapaiService->getAll();
        return view('pages.data-maskapai.index', ['title' => 'Data Maskapai', 'dataMaskapai' => $dataMaskapai]);
    }

    public function create()
    {
        // Auto-generate kode_maskapai: MK-001, MK-002, etc based on next ID
        $lastMaskapai = \App\Models\Maskapai::orderBy('id', 'desc')->first();
        $nextId = $lastMaskapai ? $lastMaskapai->id + 1 : 1;
        $newNumber = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $kodeMaskapai = 'MK-' . $newNumber;

        return view('pages.data-maskapai.create', [
            'title' => 'Tambah Data Maskapai',
            'kodeMaskapai' => $kodeMaskapai
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_maskapai' => 'required|string|unique:maskapais,kode_maskapai',
            'nama_maskapai' => 'required|string|max:255',
            'rute_penerbangan' => 'required|in:Direct,Transit',
            'lama_perjalanan' => 'required|integer|min:0',
            'harga_tiket' => 'required|numeric|min:0',
            'catatan_penerbangan' => 'nullable|string',
            'foto_maskapai' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_maskapai')) {
            $file = $request->file('foto_maskapai');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('maskapais', $filename, 'public');
            $validated['foto_maskapai'] = $path;
        }

        $this->maskapaiService->create($validated);

        return redirect()->route('data-maskapai')->with('success', 'Data maskapai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $maskapai = $this->maskapaiService->getById($id);

        if (!$maskapai) {
            return redirect()->route('data-maskapai')->with('error', 'Data maskapai tidak ditemukan');
        }

        return view('pages.data-maskapai.edit', [
            'title' => 'Edit Data Maskapai',
            'maskapai' => $maskapai
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_maskapai' => 'required|string|max:255',
            'rute_penerbangan' => 'required|in:Direct,Transit',
            'lama_perjalanan' => 'required|integer|min:0',
            'harga_tiket' => 'required|numeric|min:0',
            'catatan_penerbangan' => 'nullable|string',
            'foto_maskapai' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_maskapai')) {
            $maskapai = $this->maskapaiService->getById($id);
            if ($maskapai && $maskapai->foto_maskapai) {
                Storage::disk('public')->delete($maskapai->foto_maskapai);
            }

            $file = $request->file('foto_maskapai');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('maskapais', $filename, 'public');
            $validated['foto_maskapai'] = $path;
        }

        $maskapai = $this->maskapaiService->update($id, $validated);

        if (!$maskapai) {
            return redirect()->route('data-maskapai')->with('error', 'Data maskapai tidak ditemukan');
        }

        return redirect()->route('data-maskapai')->with('success', 'Data maskapai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->maskapaiService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data maskapai tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data maskapai berhasil dihapus']);
    }

    public function show($id)
    {
        $maskapai = $this->maskapaiService->getById($id);

        if (!$maskapai) {
            return redirect()->route('data-maskapai')->with('error', 'Data maskapai tidak ditemukan');
        }

        return view('pages.data-maskapai.show', [
            'title' => 'Detail Data Maskapai',
            'maskapai' => $maskapai
        ]);
    }

    public function export()
    {
        $maskapais = $this->maskapaiService->getAll();
        $filename = "data_maskapai_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Kode Maskapai', 'Nama Maskapai', 'Rute', 'Lama Perjalanan (Jam)', 'Harga Tiket', 'Catatan'];

        $callback = function () use ($maskapais, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($maskapais as $maskapai) {
                fputcsv($file, [
                    $maskapai->kode_maskapai,
                    $maskapai->nama_maskapai,
                    $maskapai->rute_penerbangan,
                    $maskapai->lama_perjalanan,
                    $maskapai->harga_tiket,
                    $maskapai->catatan_penerbangan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $maskapais = $this->maskapaiService->getAll();
        return view('pages.data-maskapai.print', [
            'maskapais' => $maskapais,
            'title' => 'Laporan Data Maskapai'
        ]);
    }
}
