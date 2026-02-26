<?php

namespace App\Http\Controllers;

use App\Models\PembayaranLayanan;
use Illuminate\Http\Request;

class PembayaranLayananController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/pembayaran-layanan.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' pembayaran layanan');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-layanan.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-layanan.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-layanan.delete')->exists();

        $pembayarans = PembayaranLayanan::with(['transaksiLayanan.pelanggan'])
            ->latest()
            ->get();

        return view('pages.pembayaran-layanan.index', [
            'title' => 'Data Pembayaran Layanan',
            'pembayarans' => $pembayarans,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function show($id)
    {
        // Assuming ID is TransaksiLayanan ID based on "Partner" context
        $transaksi = \App\Models\TransaksiLayanan::with(['pelanggan', 'details', 'pembayaranLayanans'])->findOrFail($id);
        
        return view('pages.pembayaran-layanan.show', [
            'title' => 'Riwayat Pembayaran - ' . ($transaksi->pelanggan->nama_pelanggan ?? 'Umum'),
            'transaksi' => $transaksi,
            'pembayarans' => $transaksi->pembayaranLayanans()->latest()->get()
        ]);
    }

    public function createPayment($id)
    {
        $this->checkPermission('create');
        
        $transaksi = \App\Models\TransaksiLayanan::with(['pelanggan'])->findOrFail($id);
        
        return view('pages.pembayaran-layanan.create_payment', [
            'title' => 'Tambah Pembayaran',
            'transaksi' => $transaksi
        ]);
    }

    public function storePayment(Request $request, $id)
    {
        $this->checkPermission('create');
        
        $transaksi = \App\Models\TransaksiLayanan::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Generate Code for Payment: PS-ID-XXX (Payment Service)
        $countPayment = PembayaranLayanan::count() + 1;
        $kodePembayaran = 'PS-' . str_pad($countPayment, 5, '0', STR_PAD_LEFT);

        PembayaranLayanan::create([
            'transaksi_layanan_id' => $transaksi->id,
            'kode_transaksi' => $kodePembayaran,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'paid', // Direct 'paid' for manual entry
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        // NOTE: We do not update total_bayar in TransaksiLayanan table directly as it doesn't seem to have that column 
        // based on previous file views (checked TransaksiLayanan model fillable). 
        // The total paid is calculated on the fly in the view.

        return redirect()->route('pembayaran-layanan.show', $transaksi->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $pembayaran = PembayaranLayanan::with('transaksiLayanan.pelanggan')->findOrFail($id);
        
        return view('pages.pembayaran-layanan.edit', [
            'title' => 'Edit Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        $pembayaran = PembayaranLayanan::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        $pembayaran->update([
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        return redirect()->route('pembayaran-layanan.index')->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $pembayaran = PembayaranLayanan::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayaran-layanan.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}
