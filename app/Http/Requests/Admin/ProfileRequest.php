<?php

namespace App\Http\Requests\Admin;

use App\Models\City;
use App\Models\User;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::USER_TYPE_ADMIN)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'max:255',
                Rule::unique(User::class, 'name')->ignore(Auth::guard(User::USER_TYPE_ADMIN)->id(), 'id')->whereNull('deleted_at')
            ],
            'email' => [
                'required', 'email', Rule::unique(User::class, 'email')->ignore(Auth::guard(User::USER_TYPE_ADMIN)->id(), 'id')->whereNull('deleted_at')
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }

    public function attributes()
    {
        return [
            'password' => __('validation.attributes.new_password')
        ];
    }
}
