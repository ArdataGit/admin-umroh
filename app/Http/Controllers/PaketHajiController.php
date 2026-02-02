<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaketHajiService;
use App\Models\PaketHaji;
use App\Models\Maskapai;
use App\Models\Hotel;

class PaketHajiController extends Controller
{
    protected $paketHajiService;

    public function __construct(PaketHajiService $paketHajiService)
    {
        $this->paketHajiService = $paketHajiService;
    }

    public function index()
    {
        $paketHajis = $this->paketHajiService->getAll();
        return view('pages.paket-haji.index', ['title' => 'Data Paket Haji', 'paketHajis' => $paketHajis]);
    }

    public function create()
    {
        // Auto-generate kode_paket: PH-001, PH-002, etc.
        $lastPaket = PaketHaji::orderBy('id', 'desc')->first();
        $lastNumber = $lastPaket ? intval(substr($lastPaket->kode_paket, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $kodePaket = 'PH-' . $newNumber;

        $maskapais = Maskapai::all();
        $hotelsMekkah = Hotel::where('lokasi_hotel', 'Makkah')->orWhere('lokasi_hotel', 'Mekkah')->get();
        $hotelsMadinah = Hotel::where('lokasi_hotel', 'Madinah')->get();
        $hotelsTransit = Hotel::all(); 

        return view('pages.paket-haji.create', [
            'title' => 'Tambah Paket Haji',
            'kodePaket' => $kodePaket,
            'maskapais' => $maskapais,
            'hotelsMekkah' => $hotelsMekkah,
            'hotelsMadinah' => $hotelsMadinah,
            'hotelsTransit' => $hotelsTransit
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_paket' => 'required|string|unique:paket_hajis,kode_paket',
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

        $this->paketHajiService->create($validated);

        return redirect()->route('paket-haji')->with('success', 'Paket haji berhasil ditambahkan');
    }

    public function edit($id)
    {
        $paketHaji = $this->paketHajiService->getById($id);
        if (!$paketHaji) {
            return redirect()->route('paket-haji')->with('error', 'Paket haji tidak ditemukan');
        }

        $maskapais = Maskapai::all();
        $hotelsMekkah = Hotel::where('lokasi_hotel', 'Makkah')->orWhere('lokasi_hotel', 'Mekkah')->get();
        $hotelsMadinah = Hotel::where('lokasi_hotel', 'Madinah')->get();
        $hotelsTransit = Hotel::all();

        return view('pages.paket-haji.edit', [
            'title' => 'Edit Paket Haji',
            'paketHaji' => $paketHaji,
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

        $this->paketHajiService->update($id, $validated);

        return redirect()->route('paket-haji')->with('success', 'Paket haji berhasil diperbarui');
    }

    public function destroy($id)
    {
        $deleted = $this->paketHajiService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Paket haji tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Paket haji berhasil dihapus']);
    }

    public function show($id)
    {
        $paketHaji = $this->paketHajiService->getById($id);
        if (!$paketHaji) {
            return redirect()->route('paket-haji')->with('error', 'Paket haji tidak ditemukan');
        }

        return view('pages.paket-haji.show', [
            'title' => 'Detail Paket Haji',
            'paketHaji' => $paketHaji
        ]);
    }
}
