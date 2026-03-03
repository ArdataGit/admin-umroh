<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate a unique code by finding the highest existing numeric suffix.
     *
     * @param string $modelClass The fully qualified model class name
     * @param string $column The column name containing the code
     * @param string $prefix The prefix of the code (e.g., 'SO-', 'PS-')
     * @param int $padding The number of zeros to pad the numeric part
     * @return string
     */
    public static function generate($modelClass, $column, $prefix, $padding = 3)
    {
        // Get the highest value in the column that starts with the prefix
        $lastRecord = $modelClass::where($column, 'like', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING($column, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if (!$lastRecord) {
            $nextId = 1;
        } else {
            $lastCode = $lastRecord->$column;
            $lastId = (int) substr($lastCode, strlen($prefix));
            $nextId = $lastId + 1;
        }

        return $prefix . str_pad($nextId, $padding, '0', STR_PAD_LEFT);
    }
}
