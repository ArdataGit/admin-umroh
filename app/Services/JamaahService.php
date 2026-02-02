<?php

namespace App\Services;

use App\Models\Jamaah;
use Illuminate\Support\Facades\Storage;

class JamaahService
{
    public function getAll()
    {
        return Jamaah::all();
    }

    public function getById($id)
    {
        return Jamaah::find($id);
    }

    public function create($data)
    {
        $fileFields = ['foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_paspor_1', 'foto_paspor_2'];

        foreach ($fileFields as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $data[$field] = $data[$field]->store('jamaah-files', 'public');
            }
        }

        return Jamaah::create($data);
    }

    public function update($id, $data)
    {
        $jamaah = Jamaah::find($id);
        if ($jamaah) {
            $fileFields = ['foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_paspor_1', 'foto_paspor_2'];

            foreach ($fileFields as $field) {
                if (isset($data[$field]) && $data[$field]) {
                    if ($jamaah->$field) {
                        Storage::disk('public')->delete($jamaah->$field);
                    }
                    $data[$field] = $data[$field]->store('jamaah-files', 'public');
                }
            }

            $jamaah->update($data);
            return $jamaah;
        }
        return null;
    }

    public function delete($id)
    {
        $jamaah = Jamaah::find($id);
        if ($jamaah) {
            $fileFields = ['foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_paspor_1', 'foto_paspor_2'];
            foreach ($fileFields as $field) {
                if ($jamaah->$field) {
                    Storage::disk('public')->delete($jamaah->$field);
                }
            }
            $jamaah->delete();
            return true;
        }
        return false;
    }
}
