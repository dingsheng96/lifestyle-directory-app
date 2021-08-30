<?php

namespace App\Support\Services;

use App\Models\Media;
use App\Models\Banner;
use App\Helpers\FileManager;
use App\Support\Services\BaseService;

class BannerService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Banner::class);
    }

    public function store()
    {
        $this->model->title = $this->request->get('title');
        $this->model->status = $this->request->get('status');
        $this->model->description = $this->request->get('description');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storeImage();

        return $this;
    }

    public function storeImage()
    {
        if ($this->request->hasFile('image')) {

            $file  =   $this->request->file('image');

            $config = [
                'save_path'     => Banner::STORE_PATH,
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
