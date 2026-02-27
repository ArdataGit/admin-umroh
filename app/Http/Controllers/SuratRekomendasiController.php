<?php

namespace App\Http\Controllers;

use App\Models\SuratRekomendasi;
use App\Models\Jamaah;
use App\Models\KeberangkatanUmroh;
use Illuminate\Http\Request;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class SuratRekomendasiController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/surat-rekomendasi.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    public function index()
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->latest()->get();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/surat-rekomendasi.create', $permissions);
        $canEdit = $isAdmin || in_array('/surat-rekomendasi.edit', $permissions);
        $canDelete = $isAdmin || in_array('/surat-rekomendasi.delete', $permissions);

        return view('pages.surat-rekomendasi.index', [
            'title' => 'Data Surat Rekomendasi',
            'surat' => $surat,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');

        $jamaah = Jamaah::orderBy('nama_jamaah', 'asc')->get();
        $keberangkatan = KeberangkatanUmroh::where('status_keberangkatan', 'active')->latest()->get();

        return view('pages.surat-rekomendasi.create', [
            'title' => 'Buat Surat Rekomendasi',
            'jamaah' => $jamaah,
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');

        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'keberangkatan_umroh_id' => 'required|exists:keberangkatan_umrohs,id',
            'nomor_dokumen' => 'required|string',
            'kantor_imigrasi' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_kakek' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        SuratRekomendasi::create($validated);

        $jamaah = Jamaah::find($validated['jamaah_id']);
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Rekomendasi',
            'action' => 'Create',
            'keterangan' => 'Membuat surat rekomendasi untuk jamaah: ' . ($jamaah->nama_jamaah ?? 'N/A') . ' (' . $validated['nomor_dokumen'] . ')'
        ]);

        return redirect()->route('surat-rekomendasi.index')->with('success', 'Surat rekomendasi berhasil dibuat');
    }

    public function show($id)
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        return view('pages.surat-rekomendasi.show', [
            'title' => 'Detail Surat Rekomendasi',
            'surat' => $surat
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');

        $surat = SuratRekomendasi::findOrFail($id);
        $jamaah = Jamaah::orderBy('nama_jamaah', 'asc')->get();
        $keberangkatan = KeberangkatanUmroh::where('status_keberangkatan', 'active')->latest()->get();

        return view('pages.surat-rekomendasi.edit', [
            'title' => 'Edit Surat Rekomendasi',
            'surat' => $surat,
            'jamaah' => $jamaah,
            'keberangkatan' => $keberangkatan
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');

        $surat = SuratRekomendasi::findOrFail($id);

        $validated = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'keberangkatan_umroh_id' => 'required|exists:keberangkatan_umrohs,id',
            'nomor_dokumen' => 'required|string',
            'kantor_imigrasi' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_kakek' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $surat->update($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Rekomendasi',
            'action' => 'Update',
            'keterangan' => 'Memperbarui surat rekomendasi untuk jamaah: ' . ($surat->jamaah->nama_jamaah ?? 'N/A') . ' (' . $surat->nomor_dokumen . ')'
        ]);

        return redirect()->route('surat-rekomendasi.index')->with('success', 'Surat rekomendasi berhasil diperbarui');
    }
    public function exportPdf($id)
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.surat-rekomendasi.pdf', [
            'title' => 'Surat Rekomendasi - ' . $surat->nomor_dokumen,
            'surat' => $surat
        ]);
        
        return $pdf->download('Surat_Rekomendasi_' . \Illuminate\Support\Str::slug($surat->nomor_dokumen) . '.pdf');
    }

    public function printPdf($id)
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.surat-rekomendasi.pdf', [
            'title' => 'Surat Rekomendasi - ' . $surat->nomor_dokumen,
            'surat' => $surat
        ]);
        
        return $pdf->stream('Surat_Rekomendasi_' . \Illuminate\Support\Str::slug($surat->nomor_dokumen) . '.pdf');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');

        $surat = SuratRekomendasi::with('jamaah')->findOrFail($id);
        $namaJamaah = $surat->jamaah->nama_jamaah ?? 'N/A';
        $nomorDokumen = $surat->nomor_dokumen;
        
        $surat->delete();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Rekomendasi',
            'action' => 'Delete',
            'keterangan' => 'Menghapus surat rekomendasi untuk jamaah: ' . $namaJamaah . ' (' . $nomorDokumen . ')'
        ]);

        return redirect()->route('surat-rekomendasi.index')->with('success', 'Surat rekomendasi berhasil dihapus');
    }

    public function export()
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->latest()->get();
        $filename = "data_surat_rekomendasi_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No. Dokumen', 'Nama Jamaah', 'Keberangkatan', 'Kantor Imigrasi', 'Nama Ayah', 'Nama Kakek', 'Catatan', 'Tanggal Buat'];

        $callback = function() use ($surat, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($surat as $item) {
                fputcsv($file, [
                    $item->nomor_dokumen,
                    $item->jamaah->nama_jamaah ?? 'N/A',
                    $item->keberangkatanUmroh->nama_keberangkatan ?? 'N/A',
                    $item->kantor_imigrasi,
                    $item->nama_ayah,
                    $item->nama_kakek,
                    $item->catatan,
                    $item->created_at->format('d-m-Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $surat = SuratRekomendasi::with(['jamaah', 'keberangkatanUmroh'])->latest()->get();
        return view('pages.surat-rekomendasi.print-list', [
            'title' => 'Laporan Data Surat Rekomendasi',
            'surat' => $surat
        ]);
    }
}
