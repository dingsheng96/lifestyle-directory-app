<?php

namespace App\Http\Requests\Api\v1\Account;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\BaseRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends BaseRequest
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
            'old_password'  =>  ['required', 'current_password:api'],
            'new_password'  =>  ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function attributes()
    {
        return [
            'old_password'  =>  __('validation.attributes.old_password'),
            'new_password'  =>  __('validation.attributes.new_password'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }
}
