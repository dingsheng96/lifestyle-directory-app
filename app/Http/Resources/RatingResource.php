<?php

namespace App\Http\Resources;

use App\Models\User;
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
        if ($this->type == User::USER_TYPE_MERCHANT) {
            $data = [
                'scale'         => number_format($this->pivot->scale, 0),
                'created_at'    => $this->pivot->created_at->format('d M Y'),
                'review'        => $this->pivot->review,
                'merchant'      => (new MerchantResource($this))->toArray($request),
            ];
        } else {
            $data = [
                'scale'         => number_format($this->pivot->scale, 0),
                'created_at'    => $this->pivot->created_at->format('d M Y'),
                'review'        => $this->pivot->review,
                'rate_by'       => (new MemberResource($this))->withDevice(false)->toArray($request),
            ];
        }

        return $data;
    }
}
