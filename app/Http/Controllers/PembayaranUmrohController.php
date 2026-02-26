<?php

namespace App\Http\Controllers;

use App\Models\PembayaranUmroh;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PembayaranUmrohController extends Controller
{
    private function checkPermission($action)
    {
        $user = auth()->user();
        
        if ($user->role->nama_role === 'super-admin') {
            return true;
        }

        $permission = $user->role->permissions()
            ->where('permission_path', '/pembayaran-umroh.' . $action)
            ->exists();

        if (!$permission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data pembayaran umroh');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-umroh.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-umroh.edit')->exists();
        $canDelete = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-umroh.delete')->exists();

        $pembayarans = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh', 'customerUmroh.agent'])
            ->latest()
            ->get();

        return view('pages.pembayaran-umroh.index', [
            'title' => 'Data Pembayaran Umroh',
            'pembayarans' => $pembayarans,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }
    public function show($id)
    {
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh.paketUmroh', 'customerUmroh.agent'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.show', [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.edit', [
            'title' => 'Edit Pembayaran',
            'pembayaran' => $pembayaran
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'status_pembayaran' => 'required|in:pending,paid,failed,checked',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        $pembayaran = PembayaranUmroh::findOrFail($id);
        
        $pembayaran->update($validated);

        // Update Sisa Tagihan in CustomerUmroh
        $customerUmroh = $pembayaran->customerUmroh;
        // Recalculate total paid
        $totalPaid = $customerUmroh->pembayaranUmroh->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerUmroh->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerUmroh->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-umroh.index')->with('success', 'Data pembayaran berhasil diperbarui');
    }
    // History List per Jamaah (CustomerUmroh)
    public function history($id)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role->nama_role === 'super-admin';
        
        $canCreate = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-umroh.create')->exists();
        $canEdit = $isSuperAdmin || $user->role->permissions()->where('permission_path', '/pembayaran-umroh.edit')->exists();

        $customerUmroh = \App\Models\CustomerUmroh::with(['jamaah', 'keberangkatanUmroh.paketUmroh', 'pembayaranUmroh'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.history', [
            'title' => 'Riwayat Pembayaran - ' . $customerUmroh->jamaah->nama_jamaah,
            'customerUmroh' => $customerUmroh,
            'pembayarans' => $customerUmroh->pembayaranUmroh()->latest()->get(),
            'canCreate' => $canCreate,
            'canEdit' => $canEdit
        ]);
    }

    // Form Add Payment
    public function createPayment($id)
    {
        $this->checkPermission('create');
        
        $customerUmroh = \App\Models\CustomerUmroh::with(['jamaah', 'keberangkatanUmroh'])->findOrFail($id);
        
        return view('pages.pembayaran-umroh.create_payment', [
            'title' => 'Tambah Pembayaran',
            'customerUmroh' => $customerUmroh
        ]);
    }

    // Store New Payment
    public function storePayment(Request $request, $id)
    {
        $this->checkPermission('create');
        
        $customerUmroh = \App\Models\CustomerUmroh::findOrFail($id);

        $validated = $request->validate([
            'jumlah_pembayaran' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer,debit,qris,other',
            'tanggal_pembayaran' => 'required|date',
            'catatan' => 'nullable|string',
            'kode_referensi' => 'nullable|string',
        ]);

        // Create Payment
        $pembayaran = PembayaranUmroh::create([
            'customer_umroh_id' => $customerUmroh->id,
            'kode_transaksi' => 'TEMP-' . uniqid(),
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'checked', // Direct 'paid/checked' for manual entry
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'],
            'catatan' => $validated['catatan'],
            'kode_referensi' => $validated['kode_referensi']
        ]);

        // Generate Transaction Code
        $keberangkatan = $customerUmroh->keberangkatanUmroh;
        $kodeJamaah = $customerUmroh->jamaah->kode_jamaah;
        $kodeTransaksi = "INV/CR/{$kodeJamaah}/{$keberangkatan->kode_keberangkatan}/{$pembayaran->id}";
        $pembayaran->update(['kode_transaksi' => $kodeTransaksi]);

        // Update Customer Totals
        $totalPaid = $customerUmroh->pembayaranUmroh->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        
        $customerUmroh->update([
            'total_bayar' => $totalPaid,
            'sisa_tagihan' => $customerUmroh->total_tagihan - $totalPaid
        ]);

        return redirect()->route('pembayaran-umroh.history', $customerUmroh->id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function exportPdf($id)
    {
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh.paketUmroh'])->findOrFail($id);
        $customerUmroh = $pembayaran->customerUmroh;

        $total_bayar = $customerUmroh->pembayaranUmroh()->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        $sisa_pembayaran = $customerUmroh->total_tagihan - $total_bayar;

        $pdf = Pdf::loadView('pages.pembayaran-umroh.pdf', [
            'title' => 'Invoice Pembayaran Umroh - ' . $pembayaran->kode_transaksi,
            'pembayaran' => $pembayaran,
            'customerUmroh' => $customerUmroh,
            'total_bayar' => $total_bayar,
            'sisa_pembayaran' => $sisa_pembayaran
        ]);

        return $pdf->download('Invoice_' . Str::slug($pembayaran->kode_transaksi) . '.pdf');
    }

    public function printPdf($id)
    {
        $pembayaran = PembayaranUmroh::with(['customerUmroh.jamaah', 'customerUmroh.keberangkatanUmroh.paketUmroh'])->findOrFail($id);
        $customerUmroh = $pembayaran->customerUmroh;

        $total_bayar = $customerUmroh->pembayaranUmroh()->where('status_pembayaran', '!=', 'failed')->sum('jumlah_pembayaran');
        $sisa_pembayaran = $customerUmroh->total_tagihan - $total_bayar;

        $pdf = Pdf::loadView('pages.pembayaran-umroh.pdf', [
            'title' => 'Invoice Pembayaran Umroh - ' . $pembayaran->kode_transaksi,
            'pembayaran' => $pembayaran,
            'customerUmroh' => $customerUmroh,
            'total_bayar' => $total_bayar,
            'sisa_pembayaran' => $sisa_pembayaran
        ]);

        return $pdf->stream('Invoice_' . Str::slug($pembayaran->kode_transaksi) . '.pdf');
    }
}
