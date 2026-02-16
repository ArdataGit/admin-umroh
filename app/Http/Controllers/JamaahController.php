<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JamaahService;
use App\Models\Jamaah;

class JamaahController extends Controller
{
    protected $jamaahService;

    public function __construct(JamaahService $jamaahService)
    {
        $this->jamaahService = $jamaahService;
    }

    public function index()
    {
        $dataJamaah = $this->jamaahService->getAll();
        return view('pages.data-jamaah.index', ['title' => 'Data Jamaah', 'dataJamaah' => $dataJamaah]);
    }

    public function create()
    {
        // Auto-generate kode_jamaah: J-001, J-002, etc.
        $lastJamaah = Jamaah::orderBy('id', 'desc')->first();
        $lastNumber = $lastJamaah ? intval(substr($lastJamaah->kode_jamaah, 2)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeJamaah = 'J-' . $newNumber;

        return view('pages.data-jamaah.create', [
            'title' => 'Tambah Data Jamaah',
            'kodeJamaah' => $kodeJamaah
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jamaah' => 'required|string|unique:jamaahs,kode_jamaah',
            'nik_jamaah' => 'required|string|max:20',
            'nama_jamaah' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today|after:1900-01-01',
            'kontak_jamaah' => 'required|string|max:20',
            'email_jamaah' => 'nullable|email|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'alamat_jamaah' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'catatan_jamaah' => 'nullable|string',
            
            // Paspor
            'nama_paspor' => 'nullable|string|max:255',
            'nomor_paspor' => 'nullable|string|max:50',
            'kantor_imigrasi' => 'nullable|string|max:255',
            'tgl_paspor_aktif' => 'nullable|date',
            'tgl_paspor_expired' => 'nullable|date|after:tgl_paspor_aktif',

            // Files
            'foto_jamaah' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_paspor_1' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_paspor_2' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $this->jamaahService->create($validated);

        return redirect()->route('data-jamaah')->with('success', 'Data jamaah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jamaah = $this->jamaahService->getById($id);

        if (!$jamaah) {
            return redirect()->route('data-jamaah')->with('error', 'Data jamaah tidak ditemukan');
        }

        return view('pages.data-jamaah.edit', [
            'title' => 'Edit Data Jamaah',
            'jamaah' => $jamaah
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nik_jamaah' => 'required|string|max:20',
            'nama_jamaah' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today|after:1900-01-01',
            'kontak_jamaah' => 'required|string|max:20',
            'email_jamaah' => 'nullable|email|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'alamat_jamaah' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'catatan_jamaah' => 'nullable|string',
            
            // Paspor
            'nama_paspor' => 'nullable|string|max:255',
            'nomor_paspor' => 'nullable|string|max:50',
            'kantor_imigrasi' => 'nullable|string|max:255',
            'tgl_paspor_aktif' => 'nullable|date',
            'tgl_paspor_expired' => 'nullable|date|after:tgl_paspor_aktif',

            // Files
            'foto_jamaah' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_kk' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_paspor_1' => 'nullable|image|mimes:jpeg,png,jpg',
            'foto_paspor_2' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $jamaah = $this->jamaahService->update($id, $validated);

        if (!$jamaah) {
            return redirect()->route('data-jamaah')->with('error', 'Data jamaah tidak ditemukan');
        }

        return redirect()->route('data-jamaah')->with('success', 'Data jamaah berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->jamaahService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data jamaah tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data jamaah berhasil dihapus']);
    }

    public function show($id)
    {
        $jamaah = $this->jamaahService->getById($id);

        if (!$jamaah) {
            return redirect()->route('data-jamaah')->with('error', 'Data jamaah tidak ditemukan');
        }

        return view('pages.data-jamaah.show', [
            'title' => 'Detail Data Jamaah',
            'jamaah' => $jamaah
        ]);
    }

    public function printData()
    {
        $jamaahs = $this->jamaahService->getAll();
        return view('pages.data-jamaah.print', [
            'jamaahs' => $jamaahs,
            'title' => 'Laporan Data Jamaah'
        ]);
    }

    public function exportData()
    {
        $jamaahs = $this->jamaahService->getAll();

        // Set headers for Excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="data-jamaah-' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        // Start output buffering
        ob_start();
        ?>
        <table border="1">
            <thead>
                <tr style="background-color: #4F46E5; color: white; font-weight: bold;">
                    <th>No</th>
                    <th>Kode Jamaah</th>
                    <th>NIK</th>
                    <th>Nama Jamaah</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten/Kota</th>
                    <th>Provinsi</th>
                    <th>Nomor Paspor</th>
                    <th>Nama Paspor</th>
                    <th>Kantor Imigrasi</th>
                    <th>Tgl Paspor Aktif</th>
                    <th>Tgl Paspor Expired</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($jamaahs as $jamaah): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $jamaah->kode_jamaah ?></td>
                    <td><?= $jamaah->nik_jamaah ?></td>
                    <td><?= $jamaah->nama_jamaah ?></td>
                    <td><?= $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    <td><?= $jamaah->tempat_lahir ?></td>
                    <td><?= date('d/m/Y', strtotime($jamaah->tanggal_lahir)) ?></td>
                    <td><?= $jamaah->kontak_jamaah ?></td>
                    <td><?= $jamaah->email_jamaah ?? '-' ?></td>
                    <td><?= $jamaah->alamat_jamaah ?></td>
                    <td><?= $jamaah->kecamatan ?></td>
                    <td><?= $jamaah->kabupaten_kota ?></td>
                    <td><?= $jamaah->provinsi ?></td>
                    <td><?= $jamaah->nomor_paspor ?? '-' ?></td>
                    <td><?= $jamaah->nama_paspor ?? '-' ?></td>
                    <td><?= $jamaah->kantor_imigrasi ?? '-' ?></td>
                    <td><?= $jamaah->tgl_paspor_aktif ? date('d/m/Y', strtotime($jamaah->tgl_paspor_aktif)) : '-' ?></td>
                    <td><?= $jamaah->tgl_paspor_expired ? date('d/m/Y', strtotime($jamaah->tgl_paspor_expired)) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        echo ob_get_clean();
        exit;
    }
}
