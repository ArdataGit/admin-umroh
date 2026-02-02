<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HotelService;

class HotelController extends Controller
{
    //
    protected $hotelService;
    public function __construct(HotelService $hotelService){
        $this->hotelService = $hotelService;
    }
    public function index(){
        $dataHotel = $this->hotelService->getAll();
        return view('pages.data-hotel.index', ['title' => 'Data Hotel', 'dataHotel' => $dataHotel]);
    }

    public function create(){
        return view('pages.data-hotel.create', ['title' => 'Tambah Data Hotel']);
    }

    public function store(Request $request){
        // Validation
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255',
            'lokasi_hotel' => 'required|in:Mekkah,Madinah,Jeddah,Transit',
            'kontak_hotel' => 'required|string|max:20',
            'email_hotel' => 'required|email|max:255',
            'rating_hotel' => 'required|integer|min:1|max:5',
            'harga_hotel' => 'required|numeric|min:0',
            'catatan_hotel' => 'nullable|string',
        ]);

        // Auto-generate kode_hotel
        $lastHotel = \App\Models\Hotel::orderBy('id', 'desc')->first();
        $lastNumber = $lastHotel ? intval(substr($lastHotel->kode_hotel, 4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $validated['kode_hotel'] = 'HTL-' . $newNumber;

        // Create hotel
        $this->hotelService->create($validated);

        return redirect()->route('data-hotel')->with('success', 'Data hotel berhasil ditambahkan');
    }

    public function edit($id){
        $hotel = $this->hotelService->getById($id);
        
        if (!$hotel) {
            return redirect()->route('data-hotel')->with('error', 'Data hotel tidak ditemukan');
        }

        return view('pages.data-hotel.edit', [
            'title' => 'Edit Data Hotel',
            'hotel' => $hotel
        ]);
    }

    public function update(Request $request, $id){
        // Validation
        $validated = $request->validate([
            'nama_hotel' => 'required|string|max:255',
            'lokasi_hotel' => 'required|in:Mekkah,Madinah,Jeddah,Transit',
            'kontak_hotel' => 'required|string|max:20',
            'email_hotel' => 'required|email|max:255',
            'rating_hotel' => 'required|integer|min:1|max:5',
            'harga_hotel' => 'required|numeric|min:0',
            'catatan_hotel' => 'nullable|string',
        ]);

        // Update hotel
        $hotel = $this->hotelService->update($id, $validated);

        if (!$hotel) {
            return redirect()->route('data-hotel')->with('error', 'Data hotel tidak ditemukan');
        }

        return redirect()->route('data-hotel')->with('success', 'Data hotel berhasil diperbarui');
    }

    public function destroy($id){
        $deleted = $this->hotelService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Data hotel tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Data hotel berhasil dihapus']);
    }

    public function show($id){
        $hotel = $this->hotelService->getById($id);
        
        if (!$hotel) {
            return redirect()->route('data-hotel')->with('error', 'Data hotel tidak ditemukan');
        }

        return view('pages.data-hotel.show', [
            'title' => 'Detail Data Hotel',
            'hotel' => $hotel
        ]);
    }
    public function export()
    {
        $hotels = $this->hotelService->getAll();
        $filename = "data_hotel_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Kode Hotel', 'Nama Hotel', 'Lokasi', 'Kontak', 'Email', 'Rating', 'Harga', 'Catatan'];

        $callback = function() use ($hotels, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($hotels as $hotel) {
                fputcsv($file, [
                    $hotel->kode_hotel,
                    $hotel->nama_hotel,
                    $hotel->lokasi_hotel,
                    $hotel->kontak_hotel,
                    $hotel->email_hotel,
                    $hotel->rating_hotel . ' Bintang',
                    $hotel->harga_hotel,
                    $hotel->catatan_hotel
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printData()
    {
        $hotels = $this->hotelService->getAll();
        return view('pages.data-hotel.print', [
            'hotels' => $hotels,
            'title' => 'Laporan Data Hotel'
        ]);
    }
}
