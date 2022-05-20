<?php

namespace App\Imports\Locale;

use App\Models\CountryState;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class CountryStateImport implements ToModel, WithValidation, SkipsEmptyRows
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return CountryState::firstOrCreate(['name' => $row[0]]);
    }

    public function rules(): array
    {
        return [
            '*.0' => [
                'required',
                'distinct'
            ]
        ];
    }
}
