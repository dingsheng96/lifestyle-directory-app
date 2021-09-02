<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Http\Requests\Api\v1\BaseRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('create');

        return [
            'name'      =>  ['required', Rule::unique(User::class, 'name')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'phone'     =>  ['required', Rule::unique(User::class, 'mobile_no')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'email'     =>  ['required', Rule::unique(User::class, 'email')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'password'  =>  ['required', 'confirmed', Password::defaults()],
        ];
    }
}
