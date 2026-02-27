<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LayananService;
use App\Models\SystemSetting;
use App\Services\ExchangeRateService;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    protected $layananService;

    public function __construct(LayananService $layananService)
    {
        $this->layananService = $layananService;
    }

    public function index()
    {
        $dataLayanan = $this->layananService->getAll();
        return view('pages.data-layanan.index', ['title' => 'Data Layanan', 'dataLayanan' => $dataLayanan]);
    }

    public function create()
    {
        // Auto-generate kode_layanan: SR-001, SR-002, etc.
        $lastLayanan = \App\Models\Layanan::orderBy('id', 'desc')->first();
        $lastNumber = $lastLayanan ? intval(substr($lastLayanan->kode_layanan, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeLayanan = 'SR-' . $newNumber;

        ExchangeRateService::updateRates();

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.data-layanan.create', [
            'title' => 'Tambah Data Layanan',
            'kodeLayanan' => $kodeLayanan,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_layanan' => 'required|string|unique:layanans,kode_layanan',
            'jenis_layanan' => 'required|in:Pesawat,Hotel,Visa,Transport,Handling,Tour,Layanan,Lainnya',
            'nama_layanan' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_layanan' => 'required|in:Active,Non Active',
            'catatan_layanan' => 'nullable|string',
            'foto_layanan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('foto_layanan')) {
            $file = $request->file('foto_layanan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('layanans', $filename, 'public');
            $validated['foto_layanan'] = $path;
        }

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            $rateKey = match($kurs) {
                'USD' => 'kurs_usd',
                'SAR' => 'kurs_sar',
                'MYR' => 'kurs_myr',
                default => null,
            };

            $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
            $rate = $rateValue / 100;

            $validated['harga_modal_asing'] = $validated['harga_modal'];
            $validated['harga_jual_asing'] = $validated['harga_jual'];
            $validated['harga_modal'] = $validated['harga_modal'] * $rate;
            $validated['harga_jual'] = $validated['harga_jual'] * $rate;
        } else {
            $validated['harga_modal_asing'] = 0;
            $validated['harga_jual_asing'] = 0;
        }

        $this->layananService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Layanan',
            'action' => 'Create',
            'keterangan' => 'Menambahkan data layanan baru: ' . $validated['nama_layanan'] . ' (' . $validated['kode_layanan'] . ')'
        ]);

        return redirect()->route('data-layanan')->with('success', 'Data layanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $layanan = $this->layananService->getById($id);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        ExchangeRateService::updateRates();

        $kursUsd = SystemSetting::where('key', 'kurs_usd')->first()->value ?? 0;
        $kursSar = SystemSetting::where('key', 'kurs_sar')->first()->value ?? 0;
        $kursMyr = SystemSetting::where('key', 'kurs_myr')->first()->value ?? (SystemSetting::where('key', 'kurs_rm')->first()->value ?? 0);

        return view('pages.data-layanan.edit', [
            'title' => 'Edit Data Layanan',
            'layanan' => $layanan,
            'kursUsd' => $kursUsd / 100,
            'kursSar' => $kursSar / 100,
            'kursMyr' => $kursMyr / 100
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required|in:Pesawat,Hotel,Visa,Transport,Handling,Tour,Layanan,Lainnya',
            'nama_layanan' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'kurs' => 'required|in:USD,SAR,MYR,IDR',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'status_layanan' => 'required|in:Active,Non Active',
            'catatan_layanan' => 'nullable|string',
            'foto_layanan' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($request->hasFile('foto_layanan')) {
            $layanan = $this->layananService->getById($id);
            if ($layanan && $layanan->foto_layanan) {
                // Check if file exists before deleting to avoid errors
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($layanan->foto_layanan)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($layanan->foto_layanan);
                }
            }

            $file = $request->file('foto_layanan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('layanans', $filename, 'public');
            $validated['foto_layanan'] = $path;
        }

        // Handle Currency Conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            $rateKey = match($kurs) {
                'USD' => 'kurs_usd',
                'SAR' => 'kurs_sar',
                'MYR' => 'kurs_myr',
                default => null,
            };

            $rateValue = $rateKey ? (SystemSetting::where('key', $rateKey)->first()->value ?? 0) : 0;
            $rate = $rateValue / 100;

            $validated['harga_modal_asing'] = $validated['harga_modal'];
            $validated['harga_jual_asing'] = $validated['harga_jual'];
            $validated['harga_modal'] = $validated['harga_modal'] * $rate;
            $validated['harga_jual'] = $validated['harga_jual'] * $rate;
        } else {
            $validated['harga_modal_asing'] = 0;
            $validated['harga_jual_asing'] = 0;
        }

        $layanan = $this->layananService->update($id, $validated);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Layanan',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data layanan: ' . $validated['nama_layanan'] . ' (' . $layanan->kode_layanan . ')'
        ]);

        return redirect()->route('data-layanan')->with('success', 'Data layanan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $layanan = $this->layananService->getById($id);
        $namaLayanan = $layanan ? $layanan->nama_layanan : 'N/A';
        $kodeLayanan = $layanan ? $layanan->kode_layanan : 'N/A';

        $deleted = $this->layananService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data layanan tidak ditemukan'], 404);
        }

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Layanan',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data layanan: ' . $namaLayanan . ' (' . $kodeLayanan . ')'
        ]);

        return response()->json(['success' => true, 'message' => 'Data layanan berhasil dihapus']);
    }

    public function show($id)
    {
        $layanan = $this->layananService->getById($id);

        if (!$layanan) {
            return redirect()->route('data-layanan')->with('error', 'Data layanan tidak ditemukan');
        }

        return view('pages.data-layanan.show', [
            'title' => 'Detail Data Layanan',
            'layanan' => $layanan
        ]);
    }

    public function printData()
    {
        $layanans = $this->layananService->getAll();
        return view('pages.data-layanan.print', [
            'layanans' => $layanans,
            'title' => 'Laporan Data Layanan'
        ]);
    }
}
