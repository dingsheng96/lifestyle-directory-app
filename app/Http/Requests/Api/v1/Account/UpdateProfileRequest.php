<?php

namespace App\Http\Requests\Api\v1\Account;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\BaseRequest;

class UpdateProfileRequest extends BaseRequest
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
            'name'          =>  ['required', Rule::unique(User::class, 'name')->ignore($this->user()->id, 'id')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'phone'         =>  ['required', Rule::unique(User::class, 'mobile_no')->ignore($this->user()->id, 'id')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'email'         =>  ['required', Rule::unique(User::class, 'email')->ignore($this->user()->id, 'id')->where('type', User::USER_TYPE_MEMBER)->whereNull('deleted_at')],
            'profile_image' =>  ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'cover_photo'   =>  ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png']
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
