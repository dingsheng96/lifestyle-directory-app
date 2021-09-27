<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    protected $with_device = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'mobile_no'     =>  $this->mobile_no,
            'email'         =>  $this->email ?? "",
            'status'        =>  $this->status,
            'profile_image' =>  $this->profile_image->full_file_path ?? "",
            'cover_photo'   =>  $this->cover_photo->full_file_path ?? "",
        ];

        if ($this->with_device) {

            $data['device'] = $this->deviceSettings->first() ? (new DeviceResource($this->deviceSettings->first()))->toArray($request) : [];
        }

        return $data;
    }

    public function withDevice(bool $with_device = true)
    {
        $this->with_device = $with_device;

        return $this;
    }
}
