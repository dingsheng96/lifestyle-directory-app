<?php

namespace App\Imports\Locale;

use App\Models\Language;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LanguageTranslationsImport implements OnEachRow, WithValidation, SkipsEmptyRows, WithHeadingRow
{
    use Importable;

    private $country_state, $version;

    public function __construct(Language $language, $version)
    {
        $this->language =   $language;
        $this->version  =   $version;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
    }

    public function rules(): array
    {
        return [
            '*.key' => [
                'required',
                'distinct'
            ],
            '*.value' => [
                'nullable'
            ]
        ];
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $translation = $this->language->translations()
            ->firstOrNew([
                'version'   =>  $this->version,
                'key'       =>  $row['key']
            ]);

        $translation->value = $row['value'];

        if ($translation->isDirty()) {

            $this->language->translations()->save($translation);
        }
    }
}
