<?php

namespace App\Http\Requests\Api\v1\Account;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\Api\v1\BaseRequest;

class DeviceSettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('update');

        return [
            'device_id'                 =>  ['required'],
            'device_os'                 =>  ['required', 'in:ios,android,huawei'],
            'push_messaging_token'      =>  ['required'],
            'enable_push_messaging'     =>  ['required', 'boolean'],
            'enable_notification_sound' =>  ['required', 'boolean'],
        ];
    }
}
