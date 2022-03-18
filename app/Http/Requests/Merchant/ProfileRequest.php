<?php

namespace App\Http\Requests\Merchant;

use App\Models\City;
use App\Models\User;
use App\Helpers\Status;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\BranchDetail;
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
        return Auth::guard(User::USER_TYPE_MERCHANT)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'max:255', Rule::unique(User::class, 'name')->ignore(Auth::id(), 'id')->where('type', User::USER_TYPE_MERCHANT)->whereNull('deleted_at')],
            'phone'     => ['required', new PhoneFormat],
            'password'  => ['nullable', 'confirmed', Password::defaults()],

            'description'       =>  ['nullable', 'string'],
            'services'          =>  ['nullable', 'string'],
            'career_desc'       =>  ['nullable', 'string'],

            'listing_status'            =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],
            'operation.*'               =>  ['required', 'array'],
            'operation.*.start_from'    =>  ['required', 'date_format:H:i'],
            'operation.*.end_at'        =>  ['required', 'date_format:H:i'],
            'operation.*.off_day'       =>  ['nullable', 'in:on'],

            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],

            'reg_no'            =>  ['required', Rule::unique(BranchDetail::class, 'reg_no')->ignore(Auth::id(), 'branch_id')->whereNull('deleted_at')],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'instagram'         =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],

            'logo'              =>  ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert'          =>  ['nullable', 'file', 'max:2000', 'mimes:pdf'],
        ];
    }

    public function attributes()
    {
        return [
            'password'                  => __('validation.attributes.new_password'),
            'operation.*.start_from'    => __('validation.attributes.start_from'),
            'operation.*.end_at'        => __('validation.attributes.end_at'),
            'operation.*.off_day'       => __('validation.attributes.off_day'),
        ];
    }
}
