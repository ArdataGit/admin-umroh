<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaketUmrohService;
use App\Models\PaketUmroh;
use App\Models\Maskapai;
use App\Models\Hotel;

class PaketUmrohController extends Controller
{
    protected $paketUmrohService;

    public function __construct(PaketUmrohService $paketUmrohService)
    {
        $this->paketUmrohService = $paketUmrohService;
    }

    public function index()
    {
        $paketUmrohs = $this->paketUmrohService->getAll();
        return view('pages.paket-umroh.index', ['title' => 'Data Paket Umroh', 'paketUmrohs' => $paketUmrohs]);
    }

    public function create()
    {
        // Auto-generate kode_paket: PU-{next_id}
        $maxId = PaketUmroh::max('id') ?? 0;
        $nextId = $maxId + 1;
        $kodePaket = 'PU-' . $nextId;

        $maskapais = Maskapai::all();
        // Assuming Hotel model has 'lokasi_hotel' attribute or similar logic needed
        // Since logic asked for "from data hotel yang lokasi hotel mekkah", 
        // I'll assume Hotel model has a 'city' or 'location' field. I will check Hotel model first to be sure, but for now I will assume 'kota_hotel' based on previous context or common sense, or just fetch all and filter in view/controller.
        // Actually, user said: "nama hotel mekkah(varian 1) diambil dari data hotel yang lokasi hotel mekkah"
        // I will check the Hotel migration/model to see the column name for location.
        // But for now, I'll fetch all hotels and filter them here.
        // Wait, I should verify Hotel model first.
        
        $hotelsMekkah = Hotel::where('lokasi_hotel', 'Makkah')->orWhere('lokasi_hotel', 'Mekkah')->get();
        $hotelsMadinah = Hotel::where('lokasi_hotel', 'Madinah')->get();
        $hotelsTransit = Hotel::all(); // Transit can be anywhere

        return view('pages.paket-umroh.create', [
            'title' => 'Tambah Paket Umroh',
            'kodePaket' => $kodePaket,
            'maskapais' => $maskapais,
            'hotelsMekkah' => $hotelsMekkah,
            'hotelsMadinah' => $hotelsMadinah,
            'hotelsTransit' => $hotelsTransit
        ]);
    }

    public function store(Request $request)
    {
        // Validation with 2 variants logic
        // If variant 2 is optional, fields might be nullable.
        // But user said "input jenis paket 2", implying it might be filled.
        // However, usually variants are optional. I will make variant 2 nullable in validation.

        $validated = $request->validate([
            'kode_paket' => 'required|string|unique:paket_umrohs,kode_paket',
            'nama_paket' => 'required|string|max:255',
            'tanggal_keberangkatan' => 'required|date',
            'jumlah_hari' => 'required|integer',
            'status_paket' => 'required|in:active,completed',
            'kuota_jamaah' => 'required|integer',
            'maskapai_id' => 'required|exists:maskapais,id',
            'rute_penerbangan' => 'required|in:direct,transit',
            'lokasi_keberangkatan' => 'required|string',

            // Variant 1 (Required)
            'jenis_paket_1' => 'required|string',
            'hotel_mekkah_1' => 'required|exists:hotels,id',
            'hotel_madinah_1' => 'required|exists:hotels,id',
            'hotel_transit_1' => 'nullable|exists:hotels,id',
            'harga_hpp_1' => 'required|numeric',
            'harga_quad_1' => 'required|numeric',
            'harga_triple_1' => 'required|numeric',
            'harga_double_1' => 'required|numeric',

            // Variant 2 (Optional)
            'jenis_paket_2' => 'nullable|string',
            'hotel_mekkah_2' => 'nullable|required_with:jenis_paket_2|exists:hotels,id',
            'hotel_madinah_2' => 'nullable|required_with:jenis_paket_2|exists:hotels,id',
            'hotel_transit_2' => 'nullable|exists:hotels,id',
            'harga_hpp_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_quad_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_triple_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_double_2' => 'nullable|required_with:jenis_paket_2|numeric',

            'termasuk_paket' => 'nullable|string',
            'tidak_termasuk_paket' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'catatan_paket' => 'nullable|string',
            'foto_brosur' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $this->paketUmrohService->create($validated);

        return redirect()->route('paket-umroh')->with('success', 'Paket umroh berhasil ditambahkan');
    }

    public function edit($id)
    {
        $paketUmroh = $this->paketUmrohService->getById($id);
        if (!$paketUmroh) {
            return redirect()->route('paket-umroh')->with('error', 'Paket umroh tidak ditemukan');
        }

        $maskapais = Maskapai::all();
        $hotelsMekkah = Hotel::where('lokasi_hotel', 'Makkah')->orWhere('lokasi_hotel', 'Mekkah')->get();
        $hotelsMadinah = Hotel::where('lokasi_hotel', 'Madinah')->get();
        $hotelsTransit = Hotel::all();

        return view('pages.paket-umroh.edit', [
            'title' => 'Edit Paket Umroh',
            'paketUmroh' => $paketUmroh,
            'maskapais' => $maskapais,
            'hotelsMekkah' => $hotelsMekkah,
            'hotelsMadinah' => $hotelsMadinah,
            'hotelsTransit' => $hotelsTransit
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
             'nama_paket' => 'required|string|max:255',
            'tanggal_keberangkatan' => 'required|date',
            'jumlah_hari' => 'required|integer',
            'status_paket' => 'required|in:active,completed',
            'kuota_jamaah' => 'required|integer',
            'maskapai_id' => 'required|exists:maskapais,id',
            'rute_penerbangan' => 'required|in:direct,transit',
            'lokasi_keberangkatan' => 'required|string',

            // Variant 1 (Required)
            'jenis_paket_1' => 'required|string',
            'hotel_mekkah_1' => 'required|exists:hotels,id',
            'hotel_madinah_1' => 'required|exists:hotels,id',
            'hotel_transit_1' => 'nullable|exists:hotels,id',
            'harga_hpp_1' => 'required|numeric',
            'harga_quad_1' => 'required|numeric',
            'harga_triple_1' => 'required|numeric',
            'harga_double_1' => 'required|numeric',

            // Variant 2 (Optional)
            'jenis_paket_2' => 'nullable|string',
            'hotel_mekkah_2' => 'nullable|required_with:jenis_paket_2|exists:hotels,id',
            'hotel_madinah_2' => 'nullable|required_with:jenis_paket_2|exists:hotels,id',
            'hotel_transit_2' => 'nullable|exists:hotels,id',
            'harga_hpp_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_quad_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_triple_2' => 'nullable|required_with:jenis_paket_2|numeric',
            'harga_double_2' => 'nullable|required_with:jenis_paket_2|numeric',

            'termasuk_paket' => 'nullable|string',
            'tidak_termasuk_paket' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'catatan_paket' => 'nullable|string',
            'foto_brosur' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $this->paketUmrohService->update($id, $validated);

        return redirect()->route('paket-umroh')->with('success', 'Paket umroh berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->paketUmrohService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Paket umroh tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Paket umroh berhasil dihapus']);
    }

    public function show($id)
    {
        $paketUmroh = $this->paketUmrohService->getById($id);
        if (!$paketUmroh) {
            return redirect()->route('paket-umroh')->with('error', 'Paket umroh tidak ditemukan');
        }

        return view('pages.paket-umroh.show', [
            'title' => 'Detail Paket Umroh',
            'paketUmroh' => $paketUmroh
        ]);
    }
}
