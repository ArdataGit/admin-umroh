<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MaskapaiService;
use Illuminate\Support\Facades\Storage;
use App\Models\SystemSetting;
use App\Services\ExchangeRateService;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class MaskapaiController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-maskapai.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
    protected $maskapaiService;

    public function __construct(MaskapaiService $maskapaiService)
    {
        $this->maskapaiService = $maskapaiService;
    }

    public function index()
    {
        $dataMaskapai = $this->maskapaiService->getAll();

        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-maskapai.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-maskapai.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-maskapai.delete', $permissions);

        return view('pages.data-maskapai.index', [
            'title' => 'Data Maskapai',
            'dataMaskapai' => $dataMaskapai,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        ExchangeRateService::updateRates();

        // Auto-generate kode_maskapai: MK-001, MK-002, etc based on next ID
        $lastMaskapai = \App\Models\Maskapai::orderBy('id', 'desc')->first();
        $nextId = $lastMaskapai ? $lastMaskapai->id + 1 : 1;
        $newNumber = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $kodeMaskapai = 'MK-' . $newNumber;

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.data-maskapai.create', [
            'title' => 'Tambah Data Maskapai',
            'kodeMaskapai' => $kodeMaskapai,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        $validated = $request->validate([
            'kode_maskapai' => 'required|string|unique:maskapais,kode_maskapai',
            'nama_maskapai' => 'required|string|max:255',
            'rute_penerbangan' => 'required|in:Direct,Transit',
            'lama_perjalanan' => 'required|integer|min:0',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'custom_kurs' => 'nullable|numeric|min:0',
            'harga_tiket' => 'required|numeric|min:0',
            'catatan_penerbangan' => 'nullable|string',
            'foto_maskapai' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($request->hasFile('foto_maskapai')) {
            $file = $request->file('foto_maskapai');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('maskapais', $filename, 'public');
            $validated['foto_maskapai'] = $path;
        }

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            if (!empty($validated['custom_kurs'])) {
                $rate = $validated['custom_kurs'];
            } else {
                $rateKey = match($kurs) {
                    'USD' => 'kurs_usd',
                    'SAR' => 'kurs_sar',
                    'MYR' => 'kurs_myr',
                    default => null,
                };

                $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
                $rate = $rateValue / 100;
            }

            $validated['kurs_asing'] = $validated['harga_tiket'];
            $validated['harga_tiket'] = $validated['harga_tiket'] * $rate;
        } else {
            $validated['kurs_asing'] = 0;
        }

        unset($validated['custom_kurs']);

        $maskapai = $this->maskapaiService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Maskapai',
            'action' => 'Create',
            'keterangan' => 'Menambah data maskapai baru: ' . $validated['nama_maskapai'] . ' (' . $validated['kode_maskapai'] . ')'
        ]);

        return redirect()->route('data-maskapai')->with('success', 'Data maskapai berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $maskapai = $this->maskapaiService->getById($id);

        if (!$maskapai) {
            return redirect()->route('data-maskapai')->with('error', 'Data maskapai tidak ditemukan');
        }

        ExchangeRateService::updateRates();

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.data-maskapai.edit', [
            'title' => 'Edit Data Maskapai',
            'maskapai' => $maskapai,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        $validated = $request->validate([
            'nama_maskapai' => 'required|string|max:255',
            'rute_penerbangan' => 'required|in:Direct,Transit',
            'lama_perjalanan' => 'required|integer|min:0',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'custom_kurs' => 'nullable|numeric|min:0',
            'harga_tiket' => 'required|numeric|min:0',
            'catatan_penerbangan' => 'nullable|string',
            'foto_maskapai' => 'nullable|image|mimes:jpeg,png,jpg,gif',
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

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            if (!empty($validated['custom_kurs'])) {
                $rate = $validated['custom_kurs'];
            } else {
                $rateKey = match($kurs) {
                    'USD' => 'kurs_usd',
                    'SAR' => 'kurs_sar',
                    'MYR' => 'kurs_myr',
                    default => null,
                };

                $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
                $rate = $rateValue / 100;
            }

            $validated['kurs_asing'] = $validated['harga_tiket'];
            $validated['harga_tiket'] = $validated['harga_tiket'] * $rate;
        } else {
            $validated['kurs_asing'] = 0;
        }

        unset($validated['custom_kurs']);

        $maskapai = $this->maskapaiService->update($id, $validated);

        if (!$maskapai) {
            return redirect()->route('data-maskapai')->with('error', 'Data maskapai tidak ditemukan');
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Maskapai',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data maskapai: ' . $maskapai->nama_maskapai . ' (' . $maskapai->kode_maskapai . ')'
        ]);

        return redirect()->route('data-maskapai')->with('success', 'Data maskapai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        $maskapai = $this->maskapaiService->getById($id);
        if (!$maskapai) {
            return response()->json(['success' => false, 'message' => 'Data maskapai tidak ditemukan'], 404);
        }

        $namaMaskapai = $maskapai->nama_maskapai;
        $kodeMaskapai = $maskapai->kode_maskapai;
        
        $deleted = $this->maskapaiService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data maskapai'], 500);
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Maskapai',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data maskapai: ' . $namaMaskapai . ' (' . $kodeMaskapai . ')'
        ]);

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
