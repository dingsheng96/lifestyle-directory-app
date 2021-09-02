<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Http\Requests\Api\v1\BaseRequest;

class DeviceRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('guest')->setAction('create');

        return [
            'device_id'     =>  ['required'],
            'push_token'    =>  ['required']
        ];
    }
}
