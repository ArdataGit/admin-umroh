<?php

namespace App\Services;

use App\Models\PaketUmroh;
use Illuminate\Support\Facades\Storage;

class PaketUmrohService
{
    public function getAll()
    {
        return PaketUmroh::with(['maskapai', 'hotelMekkah1', 'hotelMadinah1', 'hotelTransit1', 'hotelMekkah2', 'hotelMadinah2', 'hotelTransit2'])->get();
    }

    public function getById($id)
    {
        return PaketUmroh::with(['maskapai', 'hotelMekkah1', 'hotelMadinah1', 'hotelTransit1', 'hotelMekkah2', 'hotelMadinah2', 'hotelTransit2'])->find($id);
    }

    public function create($data)
    {
        if (isset($data['foto_brosur']) && $data['foto_brosur']) {
            $data['foto_brosur'] = $data['foto_brosur']->store('paket-umroh-brosur', 'public');
        }

        return PaketUmroh::create($data);
    }

    public function update($id, $data)
    {
        $paket = PaketUmroh::find($id);
        if ($paket) {
            if (isset($data['foto_brosur']) && $data['foto_brosur']) {
                if ($paket->foto_brosur) {
                    Storage::disk('public')->delete($paket->foto_brosur);
                }
                $data['foto_brosur'] = $data['foto_brosur']->store('paket-umroh-brosur', 'public');
            }

            $paket->update($data);
            return $paket;
        }
        return null;
    }

    public function delete($id)
    {
        $paket = PaketUmroh::find($id);
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
