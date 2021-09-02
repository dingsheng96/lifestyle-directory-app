<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
            'title'         =>  Str::limit($this->title),
            'content'       =>  Str::limit(strip_tags($this->content)),
            'has_read'      =>  $this->has_read,
            'created_at'    =>  $this->created_at->format('d M Y'),
            'link'          =>  route('api.v1.notifications.show', ['notification_id' => $this->id])
        ];
    }
}
