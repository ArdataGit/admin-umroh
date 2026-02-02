<?php

namespace App\Services;

use App\Models\Produk;

class ProdukService
{
    public function getAll()
    {
        return Produk::all();
    }

    public function getById($id)
    {
        return Produk::find($id);
    }

    public function create($data)
    {
        return Produk::create($data);
    }

    public function update($id, $data)
    {
        $produk = Produk::find($id);
        if ($produk) {
            $produk->update($data);
            return $produk;
        }
        return null;
    }

    public function delete($id)
    {
        $produk = Produk::find($id);
        if ($produk) {
            $produk->delete();
            return true;
        }
        return false;
    }
}
