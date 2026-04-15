<?php

namespace App\Imports;

use App\Models\EventTag;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class EventTagImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EventTag([
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
            'name' => ['required', 'string', 'max:255', 'unique:event_tags,name'],
        ];
    }
}
