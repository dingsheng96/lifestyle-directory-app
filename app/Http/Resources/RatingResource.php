<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
            'ratedBy'   =>  $this->name,
            'scale'     =>  number_format($this->pivot->scale, 0),
            'rated_at'  =>  $this->pivot->created_at,
            'review'    =>  $this->pivot->review,
        ];
    }
}
