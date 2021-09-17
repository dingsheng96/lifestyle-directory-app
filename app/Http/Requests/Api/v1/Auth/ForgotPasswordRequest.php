<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Rules\ExistMember;
use App\Rules\ValidateMemberStatus;
use App\Http\Requests\Api\v1\BaseRequest;

class ForgotPasswordRequest extends BaseRequest
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
            'phone' =>  ['required', new ExistMember('mobile_no'), new ValidateMemberStatus('mobile_no')],
        ];
    }
}
