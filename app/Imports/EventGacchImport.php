<?php

namespace App\Imports;

use App\Models\EventGacchh;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class EventGacchImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EventGacchh([
            'name' => $row['name'],
            'description' => $row['description'] ?? null,
            'is_active' => isset($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:event_gacchs,name'],
        ];
    }
}
