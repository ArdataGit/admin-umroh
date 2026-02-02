<?php

namespace App\Services;

use App\Models\Pelanggan;
use Illuminate\Support\Facades\Storage;

class PelangganService
{
    public function getAll()
    {
        return Pelanggan::all();
    }

    public function getById($id)
    {
        return Pelanggan::find($id);
    }

    public function create($data)
    {
        if (isset($data['foto_pelanggan']) && $data['foto_pelanggan']) {
            $data['foto_pelanggan'] = $data['foto_pelanggan']->store('pelanggan-photos', 'public');
        }
        return Pelanggan::create($data);
    }

    public function update($id, $data)
    {
        $pelanggan = Pelanggan::find($id);
        if ($pelanggan) {
            if (isset($data['foto_pelanggan']) && $data['foto_pelanggan']) {
                if ($pelanggan->foto_pelanggan) {
                    Storage::disk('public')->delete($pelanggan->foto_pelanggan);
                }
                $data['foto_pelanggan'] = $data['foto_pelanggan']->store('pelanggan-photos', 'public');
            }
            $pelanggan->update($data);
            return $pelanggan;
        }
        return null;
    }

    public function delete($id)
    {
        $pelanggan = Pelanggan::find($id);
        if ($pelanggan) {
            if ($pelanggan->foto_pelanggan) {
                Storage::disk('public')->delete($pelanggan->foto_pelanggan);
            }
            $pelanggan->delete();
            return true;
        }
        return false;
    }
}
