<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    protected $listing = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'        =>  $this->id,
            'name'      =>  $this->name,
            'thumbnail' =>  $this->thumbnail->full_file_path ?? app(Media::class)->default_preview_image,
            'location'  =>  $this->address->location_city_state,
            'rating'    =>  $this->rating,
            'distance'  =>  $this->address->formatted_distance
        ];

        if (!$this->listing) {

            $data = array_merge($data, [
                'images'        => collect($this->image)
                    ->map(function ($value) {
                        return $value->full_file_path;
                    })->flatten(),
                'about'         => $this->branchDetail->description,
                'services'      => $this->branchDetail->services,
                'website'       => $this->branchDetail->website,
                'facebook'      => $this->branchDetail->facebook,
                'whatsapp'      => $this->branchDetail->whatsapp,
                'instagram'     => $this->branchDetail->instagram,
                'address'       => [
                    'full_address'  => $this->address->full_address,
                    'longitude'     => $this->address->longitude,
                    'latitude'      => $this->address->latitude
                ],
                'has_career'    => (bool) $this->careers_count > 0
            ]);
        }

        return $data;
    }

    public function listing(bool $status = true)
    {
        $this->listing = $status;

        return $this;
    }
}