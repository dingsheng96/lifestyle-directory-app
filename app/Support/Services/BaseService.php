<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Address;
use App\Helpers\FileManager;
use Illuminate\Http\Request;

class BaseService
{
    public $model, $request, $parentModel;

    public function __construct($model)
    {
        $this->model = new $model;
    }

    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getModel()
    {
        $this->model->refresh();

        return $this->model;
    }

    public function setParentModel($parentModel)
    {
        $this->parentModel = $parentModel;

        return $this;
    }

    protected function storeMedia(Media $media, array $config, $file)
    {
        $store_file = (new FileManager())->store(
            $config['save_path'],
            $file,
            $media->file_path ?? null,
        );

        $media->original_filename   = $config['filename'] ?? null;
        $media->type                = $config['type'];
        $media->path                = $config['save_path'] ?? null;
        $media->extension           = $config['extension'] ?? null;
        $media->size                = $config['filesize'] ?? null;
        $media->mime                = $config['filemime'] ?? null;
        $media->properties          = json_encode($config, JSON_UNESCAPED_UNICODE);
        $media->filename            = basename($store_file);
        $media->position            = $this->model->media()->where('type', $config['type'])->max('position') + 1 ?? 1;

        if ($media->isDirty()) {
            $this->model->media()->save($media);
        }

        return $this;
    }

    public function storeAddress()
    {
        $address = $this->model->address()->firstOr(function () {
            return new Address();
        });

        if ($this->request->has('address_1') && !empty($this->request->get('address_1'))) {
            $address->address_1 = $this->request->get('address_1');
        }

        if ($this->request->has('address_2') && !empty($this->request->get('address_2'))) {
            $address->address_2 = $this->request->get('address_2');
        }

        if ($this->request->has('postcode') && !empty($this->request->get('postcode'))) {
            $address->postcode = $this->request->get('postcode');
        }

        if ($this->request->has('city') && !empty($this->request->get('city'))) {
            $address->city_id = $this->request->get('city');
        }

        if ($this->request->has('latitude') && !empty($this->request->get('latitude'))) {
            $address->latitude = $this->request->get('latitude', 0);
        }

        if ($this->request->has('longitude') && !empty($this->request->get('longitude'))) {
            $address->longitude = $this->request->get('longitude', 0);
        }

        if ($address->isDirty()) {
            $this->model->address()->save($address);
        }

        return $this;
    }
}
