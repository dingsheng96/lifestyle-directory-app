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
        $data = [
            'token'     => $this->createToken("{$this->name}'s Access Token")->accessToken,
            'account'   => (new MemberResource($this))->toArray($request)
        ];

        return $data;
    }
}
