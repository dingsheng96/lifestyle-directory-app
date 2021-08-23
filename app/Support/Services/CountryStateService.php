<?php

namespace App\Support\Services;

use App\Helpers\FileManager;
use App\Models\CountryState;
use App\Imports\Locale\CityImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Services\BaseService;
use App\Imports\Locale\CountryStateImport;
use App\Imports\Locale\CountryStateCityImport;

class CountryStateService extends BaseService
{
    public function __construct()
    {
        parent::__construct(CountryState::class);
    }

    public function store()
    {
        $name = $this->request->get('name') ?? $this->request->get('create')['name'];

        if ($this->request->hasFile('create.file')) {

            $file = $this->request->file('create')['file'];

            if ($this->request->has('create.withCity')) {
                Excel::import(new CountryStateCityImport(), $file, null, (new FileManager())->getExcelReaderType($file->extension()));
            } else {
                Excel::import(new CountryStateImport(), $file, null, (new FileManager())->getExcelReaderType($file->extension()));
            }
        } elseif (!empty($name)) {

            $this->model->name = $name;

            if ($this->model->isDirty()) {

                $this->model->save();
            }

            $this->setModel($this->model);
        }

        return $this;
    }

    public function storeCity()
    {
        if (!empty($this->request->get('create')['name'])) {

            $this->model->cities()->updateOrCreate(['name' => $this->request->get('create')['name']]);
        } else if ($this->request->hasFile('create.file')) {

            $file = $this->request->file('create')['file'];

            Excel::import(new CityImport($this->model), $file, null, (new FileManager())->getExcelReaderType($file->extension()));
        }

        return $this;
    }
}
