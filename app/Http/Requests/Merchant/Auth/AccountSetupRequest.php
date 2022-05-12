<?php

namespace App\Http\Requests\Merchant\Auth;

use App\Models\City;
use App\Models\Category;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AccountSetupRequest extends FormRequest
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
            'agreement'         =>  ['accepted'],
            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],
            'logo'              =>  ['required', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert'          =>  ['required', 'file', 'max:2000', 'mimes:pdf,jpg,jpeg,png'],
            'category'          =>  ['required', 'exists:' . Category::class . ',id']
        ];
    }
}
