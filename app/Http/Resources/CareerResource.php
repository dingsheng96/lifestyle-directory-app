<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
{
    private $with_details = false;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id'            =>  $this->id,
            'position'      =>  $this->position,
            'merchant'      =>  $this->branch->name,
            'location'      =>  $this->branch->address->location_city_state,
            'show_salary'   =>  $this->show_salary,
            'min_salary'    =>  number_format($this->formatted_min_salary, 2),
            'max_salary'    =>  number_format($this->formatted_max_salary, 2),
        ];

        if ($this->with_details) {

            $data = array_merge($data, [
                'logo'          =>  $this->branch->logo->full_file_path,
                'about'         =>  $this->about,
                'description'   =>  $this->description,
                'benefit'       =>  $this->benefit,
                'gallery'       =>  collect($this->branch->image)->map(function ($item) {
                    return $item->full_file_path;
                })->values(),
                'apply_via'     => [
                    'contact_no'    =>  $this->contact_no,
                    'whatsapp'      =>  $this->whatsapp,
                    'email'         =>  $this->email,
                    'website'       =>  $this->website
                ],
            ]);
        }

        return $data;
    }

    public function withDetails(bool $with_details = true)
    {
        $this->with_details = $with_details;

        return $this;
    }
}
