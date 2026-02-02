<?php

namespace App\Services;

use App\Models\TabunganUmroh;

class TabunganUmrohService
{
    public function getAll()
    {
        return TabunganUmroh::with('jamaah')->get();
    }

    public function getById($id)
    {
        return TabunganUmroh::with('jamaah')->find($id);
    }

    public function create($data)
    {
        return TabunganUmroh::create($data);
    }

    public function update($id, $data)
    {
        $tabungan = TabunganUmroh::find($id);
        if ($tabungan) {
            $tabungan->update($data);
            return $tabungan;
        }
        return null;
    }

    public function delete($id)
    {
        $tabungan = TabunganUmroh::find($id);
        if ($tabungan) {
            $tabungan->delete();
            return true;
        }
        return false;
    }
}
