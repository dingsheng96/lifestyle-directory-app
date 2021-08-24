<?php

namespace App\Exports\Locale;

use App\Models\Language;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LanguageTranslationsExport implements FromCollection, WithHeadings
{
    private $language, $version;

    public function __construct(Language $language, $version)
    {
        $this->language =   $language;
        $this->version  =   $version;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->language->translations()
            ->select('key', 'value')
            ->where('version', $this->version)
            ->get();
    }

    public function headings(): array
    {
        return [
            'key', 'value'
        ];
    }
}
