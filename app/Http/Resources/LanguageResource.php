<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LanguageTranslationResource;

class LanguageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $translations = $this->translations->where('version', $this->current_version);

        return array_merge(['name' =>  $this->name], (new LanguageTranslationResource($translations))->toArray($request));
    }
}
