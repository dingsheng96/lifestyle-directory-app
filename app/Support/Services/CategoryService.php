<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Category;
use App\Helpers\FileManager;
use App\Support\Services\BaseService;

class CategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    public function store()
    {
        $this->model->name = $this->request->get('name');
        $this->model->description = $this->request->get('description');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->setModel($this->model);

        $this->storeImage();

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('image')) {

            $file  =   $this->request->file('image');

            $config = [
                'save_path'     => Category::STORE_PATH,
                'type'          => Media::TYPE_IMAGE,
                'filemime'      => (new FileManager())->getMimesType($file->getClientOriginalExtension()),
                'filename'      => $file->getClientOriginalName(),
                'extension'     => $file->getClientOriginalExtension(),
                'filesize'      => $file->getSize(),
            ];

            $media = $this->model->media()
                ->firstOr(function () {
                    return new Media();
                });

            return $this->storeMedia($media, $config, $file);
        }

        return $this;
    }
}
