<?php

namespace App\Http\Controllers;

use App\Services\KotaService;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    protected $kotaService;

    public function __construct(KotaService $kotaService)
    {
        $this->kotaService = $kotaService;
    }

    public function index()
    {
        $kotas = $this->kotaService->getAll();
        return view('pages.data-kota.index', [
            'title' => 'Data Kota',
            'kotas' => $kotas
        ]);
    }

    public function create()
    {
        return view('pages.data-kota.create', [
            'title' => 'Tambah Data Kota'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kota' => 'required|string|unique:kotas,kode_kota',
            'nama_kota' => 'required|string|max:255',
        ]);

        $this->kotaService->create($validated);

        return redirect()->route('data-kota.index')->with('success', 'Data kota berhasil ditambahkan');
    }

    public function show($id)
    {
        $kota = $this->kotaService->getById($id);
        return view('pages.data-kota.show', [
            'title' => 'Detail Kota',
            'kota' => $kota
        ]);
    }

    public function edit($id)
    {
        $kota = $this->kotaService->getById($id);
        return view('pages.data-kota.edit', [
            'title' => 'Edit Kota',
            'kota' => $kota
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode_kota' => 'required|string|unique:kotas,kode_kota,' . $id,
            'nama_kota' => 'required|string|max:255',
        ]);

        $this->kotaService->update($id, $validated);

        return redirect()->route('data-kota.index')->with('success', 'Data kota berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->kotaService->delete($id);
        return response()->json(['success' => true]);
    }
}
