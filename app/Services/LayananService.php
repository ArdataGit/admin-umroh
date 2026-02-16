<?php

namespace App\Services;

use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;

class LayananService
{
    public function getAll()
    {
        return Layanan::all();
    }

    public function getById($id)
    {
        return Layanan::find($id);
    }

    public function create($data)
    {
        if (isset($data['foto_layanan']) && $data['foto_layanan'] instanceof \Illuminate\Http\UploadedFile) {
            $data['foto_layanan'] = $data['foto_layanan']->store('layanan-photos', 'public');
        }
        return Layanan::create($data);
    }

    public function update($id, $data)
    {
        $layanan = Layanan::find($id);
        if ($layanan) {
            if (isset($data['foto_layanan'])) {
                 if ($data['foto_layanan'] instanceof \Illuminate\Http\UploadedFile) {
                    if ($layanan->foto_layanan) {
                        Storage::disk('public')->delete($layanan->foto_layanan);
                    }
                    $data['foto_layanan'] = $data['foto_layanan']->store('layanan-photos', 'public');
                }
                // If it's a string (path) already, we just update it directly, 
                // assuming controller handled the deletion of old file if necessary (which it does not fully, but let's check)
            }
            $layanan->update($data);
            return $layanan;
        }
        return null;
    }

    public function delete($id)
    {
        $layanan = Layanan::find($id);
        if ($layanan) {
            if ($layanan->foto_layanan) {
                Storage::disk('public')->delete($layanan->foto_layanan);
            }
            $layanan->delete();
            return true;
        }
        return false;
    }
}
