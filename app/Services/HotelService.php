<?php

namespace App\Services;

use App\Models\Hotel;

class HotelService
{
    public function getAll()
    {
        return Hotel::all();
    }

    public function getById($id)
    {
        return Hotel::find($id);
    }

    public function create($data)
    {
        return Hotel::create($data);
    }

    public function update($id, $data)
    {
        $hotel = Hotel::find($id);
        if ($hotel) {
            $hotel->update($data);
            return $hotel;
        }
        return null;
    }

    public function delete($id)
    {
        $hotel = Hotel::find($id);
        if ($hotel) {
            $hotel->delete();
            return true;
        }
        return false;
    }
}
