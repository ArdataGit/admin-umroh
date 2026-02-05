<?php

namespace App\Services;

use App\Models\Kota;

class KotaService
{
    public function getAll()
    {
        return Kota::latest()->get();
    }

    public function getById($id)
    {
        return Kota::findOrFail($id);
    }

    public function create($data)
    {
        return Kota::create($data);
    }

    public function update($id, $data)
    {
        $kota = $this->getById($id);
        $kota->update($data);
        return $kota;
    }

    public function delete($id)
    {
        $kota = $this->getById($id);
        return $kota->delete();
    }
}
