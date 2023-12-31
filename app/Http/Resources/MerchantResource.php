<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    protected $listing = true;
    protected $similar_merchants = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->loadCount('careers');

        $data = [
            'id'        =>  $this->id,
            'name'      =>  $this->name,
            'mobile_no' =>  $this->mobile_no,
            'email'     =>  $this->email ?? "",
            'status'    =>  $this->status,
            'thumbnail' =>  $this->thumbnail->full_file_path ?? app(Media::class)->system_logo_as_preview_image,
            'location'  =>  $this->address->location_city_state,
            'rating'    =>  $this->rating,
            'distance'  =>  $this->address->formatted_distance,
            'favourite' =>  $this->checkUserFavouriteStatus($request->user()),
            'has_career' => (bool) $this->careers_count > 0,
        ];

        if (!$this->listing) {

            $data = array_merge($data, [
                'images' => collect($this->image)
                    ->map(function ($value) {
                        return $value->full_file_path;
                    })->flatten(),
                'reviews_count'     => $this->ratings_count,
                'share_link'        => "",
                'about'             => $this->branchDetail->description,
                'services'          => $this->branchDetail->services,
                'business_hours'    => $this->operationHours->map(function ($value) {
                    return [
                        'days_of_week'  =>  $value->day_name,
                        'off_day'       =>  $value->day_off,
                        'start_from'    =>  $value->start,
                        'end_at'        =>  $value->end
                    ];
                }),
                'address'           => [
                    'full_address'  => $this->address->full_address,
                    'longitude'     => number_format($this->address->longitude, 12),
                    'latitude'      => number_format($this->address->latitude, 12)
                ],
                'career_description' => $this->branchDetail->career_description,
                'similar_merchants' => parent::collection($this->similar_merchants)->toArray($request)
            ]);

            $data = array_merge($data, collect($this->userSocialMedia)
                ->mapWithKeys(function ($item) {
                    return [$item->media_key => $item->media_value];
                })->toArray());
        }

        return $data;
    }

    public function listing(bool $status = true)
    {
        $this->listing = $status;

        return $this;
    }

    public function similarMerchant($similar_merchants)
    {
        $this->similar_merchants = $similar_merchants;

        return $this;
    }
}
