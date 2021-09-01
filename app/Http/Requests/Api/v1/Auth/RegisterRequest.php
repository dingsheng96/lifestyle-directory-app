<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Models\User;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use App\Http\Requests\Api\v1\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('create')->setLog('register');

        return [
            'name'      =>  ['required', Rule::unique(User::class, 'name')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'phone'     =>  ['required', Rule::unique(User::class, 'mobile_no')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'email'     =>  ['required', Rule::unique(User::class, 'email')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'password'  =>  ['required', 'confirmed', new PasswordFormat]
        ];
    }
}
