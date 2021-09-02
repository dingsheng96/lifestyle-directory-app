<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'image'     =>  $this->media->full_file_path,
            'link'      =>  route('api.v1.banners.show', ['banner_id' => $this->id])
        ];
    }
}
