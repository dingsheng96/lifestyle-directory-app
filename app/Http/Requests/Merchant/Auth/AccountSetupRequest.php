<?php

namespace App\Http\Requests\Merchant\Auth;

use App\Models\City;
use App\Models\User;
use App\Models\Category;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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
        $user = $this->route('user');

        $rules = [
            'agreement' => ['accepted'],
            'logo' =>  ['required', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert' =>  ['required', 'file', 'max:2000', 'mimes:pdf,jpg,jpeg,png'],
        ];

        if ($user->is_main_branch) {
            $rules += [
                'address_1'         =>  ['required', 'min:3', 'max:255'],
                'address_2'         =>  ['nullable'],
                'postcode'          =>  ['required', 'digits:5'],
                'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
                'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],
                'pic_phone'         =>  ['required', new PhoneFormat],
                'pic_email'         =>  ['required', 'email'],
                'category'          =>  ['required', 'exists:' . Category::class . ',id']
            ];
        } elseif ($user->is_sub_branch) {
            $rules += [
                'password' => ['required', 'confirmed', Password::defaults()],
            ];
        }

        return $rules;
    }
}
