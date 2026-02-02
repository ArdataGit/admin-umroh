<?php

namespace App\Services;

use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;

class KaryawanService
{
    public function getAll()
    {
        return Karyawan::all();
    }

    public function getById($id)
    {
        return Karyawan::find($id);
    }

    public function create($data)
    {
        if (isset($data['foto_karyawan']) && $data['foto_karyawan'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['foto_karyawan']->store('karyawan', 'public');
            $data['foto_karyawan'] = $path;
        }

        return Karyawan::create($data);
    }

    public function update($id, $data)
    {
        $karyawan = Karyawan::find($id);
        if ($karyawan) {
            if (isset($data['foto_karyawan']) && $data['foto_karyawan'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old photo if exists
                if ($karyawan->foto_karyawan) {
                    Storage::disk('public')->delete($karyawan->foto_karyawan);
                }
                $path = $data['foto_karyawan']->store('karyawan', 'public');
                $data['foto_karyawan'] = $path;
            }

            $karyawan->update($data);
            return $karyawan;
        }
        return null;
    }

    public function delete($id)
    {
        $karyawan = Karyawan::find($id);
        if ($karyawan) {
            if ($karyawan->foto_karyawan) {
                Storage::disk('public')->delete($karyawan->foto_karyawan);
            }
            $karyawan->delete();
            return true;
        }
        return false;
    }
}
