<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageTranslationResource extends JsonResource
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
            'label_version' => $this->groupBy('version')->keys()->unique()->first(),
            'label'         => $this->mapWithKeys(function ($value) {
                return [$value->key => $value->value ?? ''];
            }),
        ];
    }
}
