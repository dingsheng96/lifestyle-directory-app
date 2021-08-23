<?php

namespace App\Imports\Locale;

use App\Models\CountryState;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class CountryStateCityImport implements ToModel, WithValidation, SkipsEmptyRows
{
    use Importable;

    public $country_states;

    public $rowCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $cities = [];

        if ($this->rowCount > 0) {
            foreach ($row as $colIndex => $col) {
                if ($colIndex > count($this->country_states)) {
                    break;
                }

                if (!empty($col)) {
                    $cities[$colIndex] = $this->country_states[$colIndex]->cities()
                        ->firstOrCreate(['name' => $col]);
                }
            }

            $this->rowCount++;

            return $cities;
        }

        // states/provinces in first row
        $this->country_states = [];

        foreach ($row as $colIndex => $col) {
            if (empty($col)) {
                break;
            }

            $this->country_states[$colIndex] = CountryState::firstOrcreate(['name' => $col]);
        }

        $this->rowCount++;

        return $this->country_states;
    }

    public function rules(): array
    {
        return [
            '*' => [
                'nullable'
            ]
        ];
    }
}
