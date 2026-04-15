<?php

namespace App\Imports;

use App\Models\EventCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class EventCategoryImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new EventCategory([
            'category_name' => $row['category_name'],
            'description' => $row['description'] ?? null,
            'community' => $row['community'] ?? null,
            'gacchh' => $row['gacchh'] ?? null,
            'tags' => $row['tags'] ?? null,
            'is_active' => isset($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_name' => ['required', 'string', 'max:255', 'unique:event_categories,category_name'],
        ];
    }
}
