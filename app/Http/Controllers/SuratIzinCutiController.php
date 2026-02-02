<?php

namespace App\Http\Controllers;

use App\Models\SuratIzinCuti;
use App\Models\Jamaah;
use App\Models\KeberangkatanUmroh;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuratIzinCutiController extends Controller
{
    public function index()
    {
        $surat = SuratIzinCuti::with(['jamaah', 'keberangkatanUmroh'])->latest()->get();
        return view('pages.surat-izin-cuti.index', [
            'title' => 'Data Surat Izin Cuti',
            'surat' => $surat
        ]);
    }

    public function create()
    {
        $jamaah = Jamaah::orderBy('nama_jamaah', 'asc')->get();
        $keberangkatan = KeberangkatanUmroh::where('status_keberangkatan', 'active')->latest()->get();

        return view('pages.surat-izin-cuti.create', [
            'title' => 'Buat Surat Izin Cuti',
            'jamaah' => $jamaah,
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'keberangkatan_umroh_id' => 'required|exists:keberangkatan_umrohs,id',
            'nomor_dokumen' => 'required|string',
            'kantor_instansi' => 'required|string',
            'nik_instansi' => 'nullable|string',
            'jabatan_instansi' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_kakek' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        SuratIzinCuti::create($validated);

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil dibuat');
    }

    public function edit($id)
    {
        $surat = SuratIzinCuti::findOrFail($id);
        $jamaah = Jamaah::orderBy('nama_jamaah', 'asc')->get();
        $keberangkatan = KeberangkatanUmroh::where('status_keberangkatan', 'active')->latest()->get();

        return view('pages.surat-izin-cuti.edit', [
            'title' => 'Edit Surat Izin Cuti',
            'surat' => $surat,
            'jamaah' => $jamaah,
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function update(Request $request, $id)
    {
        $surat = SuratIzinCuti::findOrFail($id);

        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'keberangkatan_umroh_id' => 'required|exists:keberangkatan_umrohs,id',
            'nomor_dokumen' => 'required|string',
            'kantor_instansi' => 'required|string',
            'nik_instansi' => 'nullable|string',
            'jabatan_instansi' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_kakek' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $surat->update($validated);

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil diperbarui');
    }

    public function destroy($id)
    {
        $surat = SuratIzinCuti::findOrFail($id);
        $surat->delete();

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil dihapus');
    }

    public function exportPdf($id)
    {
        $surat = SuratIzinCuti::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.surat-izin-cuti.pdf', [
            'title' => 'Surat Izin Cuti - ' . $surat->nomor_dokumen,
            'surat' => $surat
        ]);
        
        return $pdf->download('Surat_Izin_Cuti_' . Str::slug($surat->nomor_dokumen) . '.pdf');
    }
}
