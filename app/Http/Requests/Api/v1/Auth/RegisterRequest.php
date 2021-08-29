<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Models\User;
use App\Helpers\Response;
use App\Rules\PasswordFormat;
use App\Http\Requests\Api\v1\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('create')->isAuth();

        return [
            'name'      =>  ['required', 'unique:' . User::class . ',name'],
            'phone'     =>  ['required', 'unique:' . User::class . ',mobile_no'],
            'email'     =>  ['required', 'unique:' . User::class . ',email'],
            'password'  =>  ['required', 'confirmed', new PasswordFormat]
        ];
    }
}
