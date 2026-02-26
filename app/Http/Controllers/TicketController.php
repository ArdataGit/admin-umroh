<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketService;
use App\Models\Ticket;
use App\Models\Maskapai;
use App\Models\HistoryAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $ticketService;


    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    private function checkPermission($action)
    {
        $user = auth()->user();
        
        // Super-admin has full access
        if ($user->role && $user->role->name === 'super-admin') {
            return true;
        }

        $permission = "/data-tiket.{$action}";
        $hasPermission = $user->role && $user->role->permissions()
            ->where('menu_path', $permission)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk ' . $action . ' data tiket.');
        }

        return true;
    }

    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role && $user->role->name === 'super-admin';
        
        $canCreate = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/data-tiket.create')->exists());
        $canEdit = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/data-tiket.edit')->exists());
        $canDelete = $isSuperAdmin || ($user->role && $user->role->permissions()->where('menu_path', '/data-tiket.delete')->exists());

        $tickets = $this->ticketService->getAll();
        return view('pages.data-ticket.index', [
            'title' => 'Data Ticket',
            'tickets' => $tickets,
            'canCreate' => $canCreate,
            'canEdit' => $canEdit,
            'canDelete' => $canDelete
        ]);
    }

    public function create()
    {
        $this->checkPermission('create');
        
        // Auto-generate kode_tiket: TK-001, TK-002, etc.
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $lastNumber = $lastTicket ? intval(substr($lastTicket->kode_tiket, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeTiket = 'TK-' . $newNumber;

        $maskapais = Maskapai::all();
        
        $rateService = new \App\Services\ExchangeRateService();
        $kursUsd = $rateService->getRate('USD');
        $kursSar = $rateService->getRate('SAR');
        $kursMyr = $rateService->getRate('MYR');

        return view('pages.data-ticket.create', [
            'title' => 'Tambah Ticket',
            'kodeTiket' => $kodeTiket,
            'maskapais' => $maskapais,
            'kursUsd' => $kursUsd,
            'kursSar' => $kursSar,
            'kursMyr' => $kursMyr,
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission('create');
        
        // Strip dot separators before validation
        if ($request->has('harga_modal')) $request->merge(['harga_modal' => str_replace('.', '', $request->harga_modal)]);
        if ($request->has('harga_jual')) $request->merge(['harga_jual' => str_replace('.', '', $request->harga_jual)]);
        if ($request->has('custom_kurs')) $request->merge(['custom_kurs' => str_replace('.', '', $request->custom_kurs)]);

        $validated = $request->validate([
            'kode_tiket' => 'required|string|unique:tickets,kode_tiket',
            'jenis_tiket' => 'required|in:Ekonomi,Bisnis',
            'nama_tiket' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pax',
            'maskapai_id' => 'required|exists:maskapais,id',
            'kode_maskapai' => 'required|string|max:50',
            'rute_tiket' => 'required|string|max:255',
            'kode_pnr' => 'required|string|max:50',
            'jumlah_tiket' => 'required|integer',
            'tanggal_keberangkatan' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'tanggal_kepulangan' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'jumlah_hari' => 'required|integer',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kurs' => 'required|string|in:IDR,USD,SAR,MYR', // Added validation
            'custom_kurs' => 'nullable|numeric',
            'status_tiket' => 'required|in:active,non-active',
            'kode_tiket_1' => 'nullable|string',
            'kode_tiket_2' => 'nullable|string',
            'kode_tiket_3' => 'nullable|string',
            'kode_tiket_4' => 'nullable|string',
            'catatan_tiket' => 'nullable|string',
            'foto_tiket' => 'nullable|image|mimes:jpeg,png,jpg,gif',
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
            
            $validated['harga_modal_asing'] = $validated['harga_modal'];
            $validated['harga_jual_asing'] = $validated['harga_jual'];
            
            // Konversi ke Rupiah
            $validated['harga_modal'] = $validated['harga_modal'] * $rate;
            $validated['harga_jual'] = $validated['harga_jual'] * $rate;
        } else {
            $validated['harga_modal_asing'] = 0;
            $validated['harga_jual_asing'] = 0;
        }

        if ($request->hasFile('foto_tiket')) {
            $path = $request->file('foto_tiket')->store('tickets', 'public');
            $validated['foto_tiket'] = $path;
        }

        $this->ticketService->create($validated);

        // Pencatatan History Action
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Ticket',
            'action' => 'Create',
            'keterangan' => 'Menambahkan data tiket baru: ' . $validated['nama_tiket'] . ' (' . $validated['kode_tiket'] . ')'
        ]);

        return redirect()->route('data-ticket')->with('success', 'Ticket berhasil ditambahkan');
    }

    public function edit($id)
    {
        $this->checkPermission('edit');
        
        $ticket = $this->ticketService->getById($id);
        if (!$ticket) {
            return redirect()->route('data-ticket')->with('error', 'Ticket tidak ditemukan');
        }

        $maskapais = Maskapai::all();
        
        $rateService = new \App\Services\ExchangeRateService();
        $kursUsd = $rateService->getRate('USD');
        $kursSar = $rateService->getRate('SAR');
        $kursMyr = $rateService->getRate('MYR');

        return view('pages.data-ticket.edit', [
            'title' => 'Edit Ticket',
            'ticket' => $ticket,
            'maskapais' => $maskapais,
            'kursUsd' => $kursUsd,
            'kursSar' => $kursSar,
            'kursMyr' => $kursMyr,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission('edit');
        
        // Strip dot separators before validation
        if ($request->has('harga_modal')) $request->merge(['harga_modal' => str_replace('.', '', $request->harga_modal)]);
        if ($request->has('harga_jual')) $request->merge(['harga_jual' => str_replace('.', '', $request->harga_jual)]);
        if ($request->has('custom_kurs')) $request->merge(['custom_kurs' => str_replace('.', '', $request->custom_kurs)]);

        $validated = $request->validate([
            'jenis_tiket' => 'required|in:Ekonomi,Bisnis',
            'nama_tiket' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pax',
            'maskapai_id' => 'required|exists:maskapais,id',
            'kode_maskapai' => 'required|string|max:50',
            'rute_tiket' => 'required|string|max:255',
            'kode_pnr' => 'required|string|max:50',
            'jumlah_tiket' => 'required|integer',
            'tanggal_keberangkatan' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'tanggal_kepulangan' => 'required|date|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            'jumlah_hari' => 'required|integer',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kurs' => 'required|string|in:IDR,USD,SAR,MYR', // Added validation
            'custom_kurs' => 'nullable|numeric',
            'status_tiket' => 'required|in:active,non-active',
            'kode_tiket_1' => 'nullable|string',
            'kode_tiket_2' => 'nullable|string',
            'kode_tiket_3' => 'nullable|string',
            'kode_tiket_4' => 'nullable|string',
            'catatan_tiket' => 'nullable|string',
            'foto_tiket' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $ticket = Ticket::findOrFail($id);
        
        // Handle currency conversion
        $kurs = $validated['kurs'] ?? 'IDR';
        if ($kurs !== 'IDR') {
            if (!empty($validated['custom_kurs'])) {
                $rate = $validated['custom_kurs'];
            } else {
                $rateService = new \App\Services\ExchangeRateService();
                $rate = $rateService->getRate($kurs);
            }
            
            $validated['harga_modal_asing'] = $validated['harga_modal'];
            $validated['harga_jual_asing'] = $validated['harga_jual'];
            
            // Konversi ke Rupiah
            $validated['harga_modal'] = $validated['harga_modal'] * $rate;
            $validated['harga_jual'] = $validated['harga_jual'] * $rate;
        } else {
            $validated['harga_modal_asing'] = 0;
            $validated['harga_jual_asing'] = 0;
        }

        if ($request->hasFile('foto_tiket')) {
            // Delete old photo
            if ($ticket->foto_tiket && Storage::disk('public')->exists($ticket->foto_tiket)) {
                Storage::disk('public')->delete($ticket->foto_tiket);
            }
            $path = $request->file('foto_tiket')->store('tickets', 'public');
            $validated['foto_tiket'] = $path;
        }

        $this->ticketService->update($id, $validated);

        // Pencatatan History Action
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Ticket',
            'action' => 'Update',
            'keterangan' => 'Memperbarui data tiket: ' . $validated['nama_tiket'] . ' (' . $ticket->kode_tiket . ')'
        ]);

        return redirect()->route('data-ticket')->with('success', 'Ticket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $this->checkPermission('delete');
        
        $deleted = $this->ticketService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Ticket tidak ditemukan'], 404);
        }

        // Pencatatan History Action
        HistoryAction::create([
            'user_id' => Auth::id(),
            'menu' => 'Data Ticket',
            'action' => 'Delete',
            'keterangan' => 'Menghapus data tiket: ' . ($ticket ? $ticket->nama_tiket : 'ID ' . $id)
        ]);

        return response()->json(['success' => true, 'message' => 'Ticket berhasil dihapus']);
    }

    public function show($id)
    {
        $ticket = $this->ticketService->getById($id);
        if (!$ticket) {
            return redirect()->route('data-ticket')->with('error', 'Ticket tidak ditemukan');
        }

        return view('pages.data-ticket.show', [
            'title' => 'Detail Ticket',
            'ticket' => $ticket
        ]);
    }
}
