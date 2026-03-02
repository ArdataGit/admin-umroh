<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdukService;
use Illuminate\Support\Facades\Storage;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    protected $produkService;

    public function __construct(ProdukService $produkService)
    {
        $this->produkService = $produkService;
    }

    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->role && $user->role->name === 'super-admin';
        $permissions = $user && $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        
        $canCreate = $isAdmin || in_array('/data-produk.create', $permissions);
        $canEdit = $isAdmin || in_array('/data-produk.edit', $permissions);
        $canDelete = $isAdmin || in_array('/data-produk.delete', $permissions);

        $dataProduk = $this->produkService->getAll();
        return view('pages.data-produk.index', [
            'title' => 'Data Produk', 
            'dataProduk' => $dataProduk,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        // Auto-generate kode_produk: PR-001, PR-002, etc.
        $lastProduk = \App\Models\Produk::orderBy('id', 'desc')->first();
        $lastNumber = $lastProduk ? intval(substr($lastProduk->kode_produk, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeProduk = 'PR-' . $newNumber;

        $rateService = new \App\Services\ExchangeRateService();
        $kursUsd = $rateService->getRate('USD');
        $kursSar = $rateService->getRate('SAR');
        $kursMyr = $rateService->getRate('MYR');

        return view('pages.data-produk.create', [
            'title' => 'Tambah Data Produk',
            'kodeProduk' => $kodeProduk,
            'kursUsd' => $kursUsd,
            'kursSar' => $kursSar,
            'kursMyr' => $kursMyr,
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        // Strip dot separators before validation
        if ($request->has('harga_beli')) $request->merge(['harga_beli' => str_replace('.', '', $request->harga_beli)]);
        if ($request->has('harga_jual')) $request->merge(['harga_jual' => str_replace('.', '', $request->harga_jual)]);
        if ($request->has('custom_kurs')) $request->merge(['custom_kurs' => str_replace('.', '', $request->custom_kurs)]);

        $validated = $request->validate([
            'kode_produk' => 'required|string|unique:produks,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'standar_stok' => 'required|integer|min:0',
            'aktual_stok' => 'required|integer|min:0',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kurs' => 'required|string|in:IDR,USD,SAR,MYR',
            'custom_kurs' => 'nullable|numeric',
            'catatan_produk' => 'nullable|string',
            'foto_produk' => 'nullable|image',
        ]);

        // Handle currency conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            if (!empty($validated['custom_kurs'])) {
                $rate = $validated['custom_kurs'];
            } else {
                $rateService = new \App\Services\ExchangeRateService();
                $rate = $rateService->getRate($kurs);
            }
            
            $validated['harga_beli_asing'] = $validated['harga_beli'];
            $validated['harga_jual_asing'] = $validated['harga_jual'];
            
            // Konversi ke Rupiah
            $validated['harga_beli'] = $validated['harga_beli'] * $rate;
            $validated['harga_jual'] = $validated['harga_jual'] * $rate;
        } else {
            $validated['harga_beli_asing'] = 0;
            $validated['harga_jual_asing'] = 0;
        }

        \Illuminate\Support\Facades\Log::info('Store Produk Request', [
            'has_file' => $request->hasFile('foto_produk'),
            'file_valid' => $request->hasFile('foto_produk') ? $request->file('foto_produk')->isValid() : false,
            'all_files' => $request->allFiles(),
            'validated_data' => $validated
        ]);

        $path = null;
        if ($request->hasFile('foto_produk')) {
            $path = $request->file('foto_produk')->store('produk', 'public');
            $validated['foto_produk'] = $path;
            \Illuminate\Support\Facades\Log::info('File stored at: ' . $path);
        } else {
             \Illuminate\Support\Facades\Log::info('No file detected in request');
        }

        $this->produkService->create($validated);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Produk',
            'action' => 'Create',
            'keterangan' => 'Menambahkan produk baru: ' . $validated['nama_produk'] . ' (' . $validated['kode_produk'] . ')'
        ]);

        return redirect()->route('data-produk')->with('success', 'Data produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        $produk = $this->produkService->getById($id);

        if (!$produk) {
            return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        $rateService = new \App\Services\ExchangeRateService();
        $kursUsd = $rateService->getRate('USD');
        $kursSar = $rateService->getRate('SAR');
        $kursMyr = $rateService->getRate('MYR');

        return view('pages.data-produk.edit', [
            'title' => 'Edit Data Produk',
            'produk' => $produk,
            'kursUsd' => $kursUsd,
            'kursSar' => $kursSar,
            'kursMyr' => $kursMyr,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');

        // Strip dot separators before validation
        if ($request->has('harga_beli')) $request->merge(['harga_beli' => str_replace('.', '', $request->harga_beli)]);
        if ($request->has('harga_jual')) $request->merge(['harga_jual' => str_replace('.', '', $request->harga_jual)]);
        if ($request->has('custom_kurs')) $request->merge(['custom_kurs' => str_replace('.', '', $request->custom_kurs)]);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'standar_stok' => 'required|integer|min:0',
            'aktual_stok' => 'required|integer|min:0',
            'satuan_unit' => 'required|in:Pcs,Set,Pack,Dus,Lot,Pax,Room,Seat',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kurs' => 'required|string|in:IDR,USD,SAR,MYR',
            'custom_kurs' => 'nullable|numeric',
            'catatan_produk' => 'nullable|string',
            'foto_produk' => 'nullable|image',
        ]);

        $produk = $this->produkService->getById($id);
        
        if ($produk) {
            // Handle currency conversion
            $kurs = $validated['kurs'] ?? 'IDR';
            if ($kurs !== 'IDR') {
                if (!empty($validated['custom_kurs'])) {
                    $rate = $validated['custom_kurs'];
                } else {
                    $rateService = new \App\Services\ExchangeRateService();
                    $rate = $rateService->getRate($kurs);
                }
                
                $validated['harga_beli_asing'] = $validated['harga_beli'];
                $validated['harga_jual_asing'] = $validated['harga_jual'];
                
                // Konversi ke Rupiah
                $validated['harga_beli'] = $validated['harga_beli'] * $rate;
                $validated['harga_jual'] = $validated['harga_jual'] * $rate;
            } else {
                $validated['harga_beli_asing'] = 0;
                $validated['harga_jual_asing'] = 0;
            }
            if ($request->hasFile('foto_produk')) {
                if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                    Storage::disk('public')->delete($produk->foto_produk);
                }
                $validated['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
            }
            $this->produkService->update($id, $validated);

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Data Produk',
                'action' => 'Update',
                'keterangan' => 'Memperbarui data produk: ' . $produk->nama_produk . ' (' . $produk->kode_produk . ')'
            ]);
        } else {
             return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        return redirect()->route('data-produk')->with('success', 'Data produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        $produk = $this->produkService->getById($id);

        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Data produk tidak ditemukan'], 404);
        }

        if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $this->produkService->delete($id);

        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Produk',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data produk: ' . $produk->nama_produk . ' (' . $produk->kode_produk . ')'
        ]);

        return response()->json(['success' => true, 'message' => 'Data produk berhasil dihapus']);
    }

    public function show($id)
    {
        $produk = $this->produkService->getById($id);

        if (!$produk) {
            return redirect()->route('data-produk')->with('error', 'Data produk tidak ditemukan');
        }

        return view('pages.data-produk.show', [
            'title' => 'Detail Data Produk',
            'produk' => $produk
        ]);
    }

    public function printData()
    {
        $this->checkPermission('index'); // Assuming printing needs index access
        $produks = $this->produkService->getAll();
        return view('pages.data-produk.print', [
            'produks' => $produks,
            'title' => 'Laporan Data Produk'
        ]);
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->name === 'super-admin') {
            return;
        }

        $permissions = $user->role ? $user->role->permissions->pluck('menu_path')->toArray() : [];
        if (!in_array('/data-produk.' . $action, $permissions)) {
            if (request()->wantsJson()) {
                abort(403, 'Unauthorized action.');
            }
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }
    }
}
