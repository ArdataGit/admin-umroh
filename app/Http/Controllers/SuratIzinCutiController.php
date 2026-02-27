<?php

namespace App\Http\Controllers;

use App\Models\SuratIzinCuti;
use App\Models\Jamaah;
use App\Models\KeberangkatanUmroh;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class SuratIzinCutiController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/surat-izin-cuti.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    public function index()
    {
        $surat = SuratIzinCuti::with(['jamaah', 'keberangkatanUmroh'])->latest()->get();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/surat-izin-cuti.create', $permissions);
        $canEdit = $isAdmin || in_array('/surat-izin-cuti.edit', $permissions);
        $canDelete = $isAdmin || in_array('/surat-izin-cuti.delete', $permissions);

        return view('pages.surat-izin-cuti.index', [
            'title' => 'Data Surat Izin Cuti',
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

        return view('pages.surat-izin-cuti.create', [
            'title' => 'Buat Surat Izin Cuti',
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
            'kantor_instansi' => 'required|string',
            'nik_instansi' => 'nullable|string',
            'jabatan_instansi' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_kakek' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        SuratIzinCuti::create($validated);

        $jamaah = Jamaah::find($validated['jamaah_id']);
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Izin Cuti',
            'action' => 'Create',
            'keterangan' => 'Membuat surat izin cuti untuk jamaah: ' . ($jamaah->nama_jamaah ?? 'N/A') . ' (' . $validated['nomor_dokumen'] . ')'
        ]);

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil dibuat');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');

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
        $this->checkPermission('edit');

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

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Izin Cuti',
            'action' => 'Update',
            'keterangan' => 'Memperbarui surat izin cuti untuk jamaah: ' . ($surat->jamaah->nama_jamaah ?? 'N/A') . ' (' . $surat->nomor_dokumen . ')'
        ]);

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');

        $surat = SuratIzinCuti::with('jamaah')->findOrFail($id);
        $namaJamaah = $surat->jamaah->nama_jamaah ?? 'N/A';
        $nomorDokumen = $surat->nomor_dokumen;

        $surat->delete();

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Surat Izin Cuti',
            'action' => 'Delete',
            'keterangan' => 'Menghapus surat izin cuti untuk jamaah: ' . $namaJamaah . ' (' . $nomorDokumen . ')'
        ]);

        return redirect()->route('surat-izin-cuti.index')->with('success', 'Surat izin cuti berhasil dihapus');
    }

    public function show($id)
    {
        $surat = SuratIzinCuti::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        return view('pages.surat-izin-cuti.show', [
            'title' => 'Detail Surat Izin Cuti',
            'surat' => $surat
        ]);
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

    public function printPdf($id)
    {
        $surat = SuratIzinCuti::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.surat-izin-cuti.pdf', [
            'title' => 'Surat Izin Cuti - ' . $surat->nomor_dokumen,
            'surat' => $surat
        ]);
        
        return $pdf->stream('Surat_Izin_Cuti_' . Str::slug($surat->nomor_dokumen) . '.pdf');
    }
}
