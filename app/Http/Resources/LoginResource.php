<?php

namespace App\Http\Resources;

use App\Http\Resources\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'account'   => (new MemberResource($this))->toArray($request),
            'token'     => $this->createToken("{$this->name}'s Token")->accessToken,
        ];
    }
}
