<?php

namespace App\Services;

use App\Models\TabunganHaji;

class TabunganHajiService
{
    public function getAll()
    {
        return TabunganHaji::with('jamaah')->get();
    }

    public function getById($id)
    {
        return TabunganHaji::with('jamaah')->find($id);
    }

    public function create($data)
    {
        return TabunganHaji::create($data);
    }

    public function update($id, $data)
    {
        $tabungan = TabunganHaji::find($id);
        if ($tabungan) {
            $tabungan->update($data);
            return $tabungan;
        }
        return null;
    }

    public function delete($id)
    {
        $tabungan = TabunganHaji::find($id);
        if ($tabungan) {
            $tabungan->delete();
            return true;
        }
        return false;
    }
}
