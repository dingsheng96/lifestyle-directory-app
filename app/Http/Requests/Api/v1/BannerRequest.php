<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Banner;
use Illuminate\Validation\Rule;
use App\Http\Requests\Api\v1\BaseRequest;

class BannerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('banner')->setAction('read');

        return [
            'banner_id' =>  ['required', Rule::exists(Banner::class, 'id')->where('status', Banner::STATUS_PUBLISH)->whereNull('deleted_at')]
        ];
    }
}
