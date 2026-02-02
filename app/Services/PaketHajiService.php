<?php

namespace App\Services;

use App\Models\PaketHaji;
use Illuminate\Support\Facades\Storage;

class PaketHajiService
{
    public function getAll()
    {
        return PaketHaji::with(['maskapai', 'hotelMekkah1', 'hotelMadinah1', 'hotelTransit1', 'hotelMekkah2', 'hotelMadinah2', 'hotelTransit2'])->get();
    }

    public function getById($id)
    {
        return PaketHaji::with(['maskapai', 'hotelMekkah1', 'hotelMadinah1', 'hotelTransit1', 'hotelMekkah2', 'hotelMadinah2', 'hotelTransit2'])->find($id);
    }

    public function create($data)
    {
        if (isset($data['foto_brosur']) && $data['foto_brosur']) {
            $data['foto_brosur'] = $data['foto_brosur']->store('paket-haji-brosur', 'public');
        }

        return PaketHaji::create($data);
    }

    public function update($id, $data)
    {
        $paket = PaketHaji::find($id);
        if ($paket) {
            if (isset($data['foto_brosur']) && $data['foto_brosur']) {
                if ($paket->foto_brosur) {
                    Storage::disk('public')->delete($paket->foto_brosur);
                }
                $data['foto_brosur'] = $data['foto_brosur']->store('paket-haji-brosur', 'public');
            }

            $paket->update($data);
            return $paket;
        }
        return null;
    }

    public function delete($id)
    {
        $paket = PaketHaji::find($id);
        if ($paket) {
            if ($paket->foto_brosur) {
                Storage::disk('public')->delete($paket->foto_brosur);
            }
            $paket->delete();
            return true;
        }
        return false;
    }
}
