<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KaryawanService;

class KaryawanController extends Controller
{
    protected $karyawanService;

    public function __construct(KaryawanService $karyawanService)
    {
        $this->karyawanService = $karyawanService;
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-karyawan.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }

    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-karyawan.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-karyawan.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-karyawan.delete', $permissions);

        $dataKaryawan = $this->karyawanService->getAll();
        return view('pages.data-karyawan.index', [
            'title' => 'Data Karyawan',
            'dataKaryawan' => $dataKaryawan,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');

        // Auto-generate kode_karyawan: K-001, K-002, etc.
        $lastKaryawan = \App\Models\Karyawan::orderBy('id', 'desc')->first();
        $lastNumber = $lastKaryawan ? intval(substr($lastKaryawan->kode_karyawan, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeKaryawan = 'K-' . $newNumber;

        return view('pages.data-karyawan.create', [
            'title' => 'Tambah Data Karyawan',
            'kodeKaryawan' => $kodeKaryawan
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');

        $validated = $request->validate([
            'kode_karyawan' => 'required|string|unique:karyawans,kode_karyawan',
            'nik_karyawan' => 'required|string|unique:karyawans,nik_karyawan|max:16',
            'nama_karyawan' => 'required|string|max:255',
            'kontak_karyawan' => 'required|string|max:20',
            'email_karyawan' => 'required|email|unique:karyawans,email_karyawan',
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat_karyawan' => 'required|string',
            'catatan_karyawan' => 'nullable|string',
            'gaji' => 'required|numeric|min:0',
            'foto_karyawan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $this->karyawanService->create($validated);

        return redirect()->route('data-karyawan')->with('success', 'Data karyawan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');

        $karyawan = $this->karyawanService->getById($id);

        if (!$karyawan) {
            return redirect()->route('data-karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        return view('pages.data-karyawan.edit', [
            'title' => 'Edit Data Karyawan',
            'karyawan' => $karyawan
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');

        $validated = $request->validate([
            'nik_karyawan' => 'required|string|max:16|unique:karyawans,nik_karyawan,' . $id,
            'nama_karyawan' => 'required|string|max:255',
            'kontak_karyawan' => 'required|string|max:20',
            'email_karyawan' => 'required|email|unique:karyawans,email_karyawan,' . $id,
            'kabupaten_kota' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat_karyawan' => 'required|string',
            'catatan_karyawan' => 'nullable|string',
            'gaji' => 'required|numeric|min:0',
            'foto_karyawan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $karyawan = $this->karyawanService->update($id, $validated);

        if (!$karyawan) {
            return redirect()->route('data-karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        return redirect()->route('data-karyawan')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');

        $deleted = $this->karyawanService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data karyawan tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data karyawan berhasil dihapus']);
    }

    public function show($id)
    {
        $karyawan = $this->karyawanService->getById($id);

        if (!$karyawan) {
            return redirect()->route('data-karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        return view('pages.data-karyawan.show', [
            'title' => 'Detail Data Karyawan',
            'karyawan' => $karyawan
        ]);
    }

    public function export()
    {
        $karyawans = $this->karyawanService->getAll();
        $filename = "data_karyawan_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Kode Karyawan', 'NIK', 'Nama Karyawan', 'Kontak', 'Email', 'Kabupaten/Kota', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Catatan'];

        $callback = function() use ($karyawans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($karyawans as $karyawan) {
                fputcsv($file, [
                    $karyawan->kode_karyawan,
                    $karyawan->nik_karyawan,
                    $karyawan->nama_karyawan,
                    $karyawan->kontak_karyawan,
                    $karyawan->email_karyawan,
                    $karyawan->kabupaten_kota,
                    $karyawan->jenis_kelamin,
                    $karyawan->tempat_lahir,
                    $karyawan->tanggal_lahir,
                    $karyawan->alamat_karyawan,
                    $karyawan->catatan_karyawan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $karyawans = $this->karyawanService->getAll();
        return view('pages.data-karyawan.print', [
            'karyawans' => $karyawans,
            'title' => 'Laporan Data Karyawan'
        ]);
    }
}
