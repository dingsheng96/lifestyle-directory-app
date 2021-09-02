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
        $ratings = $this->ratings()->paginate(15, ['*'], 'page', $request->get('page'));

        $data = [];

        foreach ($ratings as $rating) {
            $data[] = [
                'rater'     => (new MemberResource($rating))->withDevice(false)->toArray($request),
                'scale'     => number_format($rating->pivot->scale, 0),
                'rated_at'  => $rating->pivot->created_at,
                'review'    => $rating->pivot->review,
            ];
        }

        return $data;
    }
}
