<?php

namespace App\Support\Services;

use App\Models\Config;
use App\Support\Services\BaseService;

class ConfigService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Config::class);
    }

    public function store()
    {
        foreach ($this->request->except(['_token', '_method']) as $key => $value) {

            $config = $this->model->firstOrNew(['key' => $key]);
            $config->key = $key;
            $config->value = trim($value);

            if ($config->isDirty()) {
                $config->save();
            }
        }

        return $this;
    }
}
