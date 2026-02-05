<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketService;
use App\Models\Ticket;
use App\Models\Maskapai;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $tickets = $this->ticketService->getAll();
        return view('pages.data-ticket.index', ['title' => 'Data Ticket', 'tickets' => $tickets]);
    }

    public function create()
    {
        // Auto-generate kode_tiket: TK-001, TK-002, etc.
        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $lastNumber = $lastTicket ? intval(substr($lastTicket->kode_tiket, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodeTiket = 'TK-' . $newNumber;

        $maskapais = Maskapai::all();

        return view('pages.data-ticket.create', [
            'title' => 'Tambah Ticket',
            'kodeTiket' => $kodeTiket,
            'maskapais' => $maskapais
        ]);
    }

    public function store(Request $request)
    {
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
            'tanggal_keberangkatan' => 'required|date',
            'tanggal_kepulangan' => 'required|date',
            'jumlah_hari' => 'required|integer',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'status_tiket' => 'required|in:active,non-active',
            'kode_tiket_1' => 'nullable|string',
            'kode_tiket_2' => 'nullable|string',
            'kode_tiket_3' => 'nullable|string',
            'kode_tiket_4' => 'nullable|string',
            'catatan_tiket' => 'nullable|string',
            'foto_tiket' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_tiket')) {
            $path = $request->file('foto_tiket')->store('tickets', 'public');
            $validated['foto_tiket'] = $path;
        }

        $this->ticketService->create($validated);

        return redirect()->route('data-ticket')->with('success', 'Ticket berhasil ditambahkan');
    }

    public function edit($id)
    {
        $ticket = $this->ticketService->getById($id);
        if (!$ticket) {
            return redirect()->route('data-ticket')->with('error', 'Ticket tidak ditemukan');
        }

        $maskapais = Maskapai::all();

        return view('pages.data-ticket.edit', [
            'title' => 'Edit Ticket',
            'ticket' => $ticket,
            'maskapais' => $maskapais
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_tiket' => 'required|in:Ekonomi,Bisnis',
            'nama_tiket' => 'required|string|max:255',
            'satuan_unit' => 'required|in:Pax',
            'maskapai_id' => 'required|exists:maskapais,id',
            'kode_maskapai' => 'required|string|max:50',
            'rute_tiket' => 'required|string|max:255',
            'kode_pnr' => 'required|string|max:50',
            'jumlah_tiket' => 'required|integer',
            'tanggal_keberangkatan' => 'required|date',
            'tanggal_kepulangan' => 'required|date',
            'jumlah_hari' => 'required|integer',
            'harga_modal' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'status_tiket' => 'required|in:active,non-active',
            'kode_tiket_1' => 'nullable|string',
            'kode_tiket_2' => 'nullable|string',
            'kode_tiket_3' => 'nullable|string',
            'kode_tiket_4' => 'nullable|string',
            'catatan_tiket' => 'nullable|string',
            'foto_tiket' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ticket = Ticket::findOrFail($id);

        if ($request->hasFile('foto_tiket')) {
            // Delete old photo
            if ($ticket->foto_tiket && Storage::disk('public')->exists($ticket->foto_tiket)) {
                Storage::disk('public')->delete($ticket->foto_tiket);
            }
            $path = $request->file('foto_tiket')->store('tickets', 'public');
            $validated['foto_tiket'] = $path;
        }

        $this->ticketService->update($id, $validated);

        return redirect()->route('data-ticket')->with('success', 'Ticket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->ticketService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Ticket tidak ditemukan'], 404);
        }

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
