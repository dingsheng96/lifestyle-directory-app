<?php

namespace App\Http\Requests\Merchant\Auth;

use App\Models\City;
use App\Models\User;
use App\Models\Category;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use App\Rules\UniqueMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'name'              =>  ['required', 'min:3', 'max:255'],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email', new UniqueMerchant('email')],
            'password'          =>  ['required', 'confirmed', Password::defaults()],
            'pic_name'          =>  ['required'],
            'referral_code'     =>  ['nullable', Rule::exists(User::class, 'referral_code')->where('type', 'admin')->whereNull('deleted_at')],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        $this->merge(['listing_status' => User::LISTING_STATUS_DRAFT]);
    }
}
