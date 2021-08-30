<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Language;
use App\Helpers\FileManager;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Services\BaseService;
use App\Imports\Locale\LanguageTranslationsImport;

class LanguageService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Language::class);
    }

    public function store()
    {
        $this->model->name = $this->request->get('name');
        $this->model->code = $this->request->get('code');

        if ($this->request->has('default')) {
            if ($default_language = Language::default()->first()) {
                $default_language->default = false;
                $default_language->save();
            }

            $this->model->default = true;
        }

        if ($this->request->has('use_version') || !empty($this->request->get('current_version')) || !$this->model->exists) {

            $this->model->current_version = $this->request->has('use_version')
                ? $this->request->get('new_version')
                : $this->request->get('current_version');
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storeTranslations();

        return $this;
    }

    public function storeTranslations()
    {
        if ($this->request->has('file')) {

            $version =  $this->request->get('new_version') ?? $this->request->get('version');

            $file    =  $this->request->file('file');

            Excel::import(
                new LanguageTranslationsImport($this->model, $version),
                $file,
                null,
                (new FileManager())->getExcelReaderType($file->extension())
            );
        }

        return $this;
    }
}
