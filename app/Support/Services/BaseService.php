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
            null
        );

        $media->original_filename   =   $config['filename'] ?? null;
        $media->type                =   $config['type'];
        $media->path                =   $config['save_path'] ?? null;
        $media->extension           =   $config['extension'] ?? null;
        $media->size                =   $config['filesize'] ?? null;
        $media->mime                =   $config['filemime'] ?? null;
        $media->properties          =   json_encode($config, JSON_UNESCAPED_UNICODE);
        $media->filename            =   basename($store_file);

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

        $address->address_1 = $this->request->get('address_1');
        $address->address_2 = $this->request->get('address_2');
        $address->postcode  = $this->request->get('postcode');
        $address->city_id   = $this->request->get('city');

        if ($address->isDirty()) {

            $this->model->address()->save($address);
        }

        return $this;
    }
}
