<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getAll()
    {
        return Supplier::all();
    }

    public function getById($id)
    {
        return Supplier::find($id);
    }

    public function create($data)
    {
        return Supplier::create($data);
    }

    public function update($id, $data)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->update($data);
            return $supplier;
        }
        return null;
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            return true;
        }
        return false;
    }
}
