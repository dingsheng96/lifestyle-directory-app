<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Rules\ExistMember;
use App\Rules\ValidateMemberStatus;
use App\Rules\ValidateMemberPassword;
use App\Http\Requests\Api\v1\BaseRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('authenticate');

        return [
            'phone'     =>  ['required', new ExistMember('mobile_no'), new ValidateMemberStatus('mobile_no')],
            'password'  =>  ['required', new ValidateMemberPassword('mobile_no', $this->get('phone'))],
            'device_id' =>  ['required']
        ];
    }
}
