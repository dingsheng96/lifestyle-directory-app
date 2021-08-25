<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\BranchDetail;
use App\Models\CountryState;
use App\Rules\PasswordFormat;
use App\Rules\UniqueMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['merchant.create', 'merchant.update']);
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
            'email'             =>  ['required', 'email', new UniqueMerchant('email', $this->route('branch') ?? $this->route('merchant'))],
            'password'          =>  [Rule::requiredIf(empty($this->route('merchant'))), 'nullable', new PasswordFormat, 'confirmed'],
            'status'            =>  ['required', Rule::in(array_keys((new Status())->activeStatus()))],
            'branch_status'     =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],

            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],

            'reg_no'            =>  [
                'required',
                Rule::unique(BranchDetail::class, 'reg_no')
                    ->ignore(optional($this->route('branch'))->id ?? $this->route('merchant')->id, 'branch_id')
                    ->whereNull('deleted_at')
            ],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'instagram'         =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],

            'logo'              =>  [Rule::requiredIf((empty($this->route('branch')) || empty($this->route('merchant')))), 'nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert'          =>  [Rule::requiredIf((empty($this->route('branch')) || empty($this->route('merchant')))), 'nullable', 'file', 'max:2000', 'mimes:pdf'],
            'files'             =>  ['nullable'],
            'files.*'           =>  ['image', 'mimes:jpg,jpeg,png'],
            'thumbnail'         =>  ['nullable', 'exists:' . Media::class . ',id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'files' => __('validation.attributes.file'),
            'files.*' => __('validation.attributes.file'),
        ];
    }
}
