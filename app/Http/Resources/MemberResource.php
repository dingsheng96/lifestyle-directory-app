<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'mobile_no'     =>  $this->mobile_no,
            'status'        =>  $this->status,
            'profile_image' =>  $this->profile_image->full_file_path,
            'cover_photo'   =>  $this->cover_photo->full_file_path
        ];
    }
}
