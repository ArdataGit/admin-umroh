<?php

namespace App\Services;

use App\Models\Maskapai;

class MaskapaiService
{
    public function getAll()
    {
        return Maskapai::all();
    }

    public function getById($id)
    {
        return Maskapai::find($id);
    }

    public function create($data)
    {
        return Maskapai::create($data);
    }

    public function update($id, $data)
    {
        $maskapai = Maskapai::find($id);
        if ($maskapai) {
            $maskapai->update($data);
            return $maskapai;
        }
        return null;
    }

    public function delete($id)
    {
        $maskapai = Maskapai::find($id);
        if ($maskapai) {
            $maskapai->delete();
            return true;
        }
        return false;
    }
}
