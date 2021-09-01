<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                        =>  $this->id,
            'device_id'                 =>  $this->device_id,
            'device_os'                 =>  $this->device_os,
            'status'                    =>  $this->status,
            'push_messaging_token'      =>  $this->push_messaging_token,
            'enable_push_messaging'     =>  $this->enable_push_messaging,
            'enable_notification_sound' =>  $this->enable_notification_sound
        ];
    }
}
