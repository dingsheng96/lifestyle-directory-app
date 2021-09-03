<?php

namespace App\Http\Requests\Merchant;

use App\Models\City;
use App\Models\User;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Category;
use App\Rules\PhoneFormat;
use App\Models\BranchDetail;
use App\Models\CountryState;
use App\Rules\UniqueMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
        $branch = $this->route('branch');

        return [
            'name'              =>  ['required', 'min:3', 'max:255'],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email', new UniqueMerchant('email', $branch)],
            'password'          =>  [Rule::requiredIf(empty($branch)), 'nullable', 'confirmed', Password::defaults()],
            'status'            =>  ['required', Rule::in(array_keys((new Status())->activeStatus()))],
            'listing_status'    =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],

            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],

            'reg_no'            =>  ['required', Rule::unique(BranchDetail::class, 'reg_no')->ignore($branch->id, 'branch_id')->whereNull('deleted_at')],
            'website'           =>  ['nullable', 'url'],
            'facebook'          =>  ['nullable', 'url'],
            'instagram'         =>  ['nullable', 'url'],
            'whatsapp'          =>  ['nullable', new PhoneFormat],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],

            'logo'              =>  [Rule::requiredIf(empty($branch)), 'nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert'          =>  [Rule::requiredIf(empty($branch)), 'nullable', 'file', 'max:2000', 'mimes:pdf'],
            'files'             =>  ['nullable'],
            'files.*'           =>  ['image', 'mimes:jpg,jpeg,png'],
            'thumbnail'         =>  ['nullable', 'exists:' . Media::class . ',id'],

            'operation.*'               =>  ['required', 'array'],
            'operation.*.start_from'    =>  ['required', 'date_format:H:i'],
            'operation.*.end_at'        =>  ['required', 'date_format:H:i'],
            'operation.*.off_day'       =>  ['nullable', 'in:on'],
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
            'files'         => __('validation.attributes.file'),
            'files.*'       => __('validation.attributes.file'),

            'operation.*.start_from'  => __('validation.attributes.start_from'),
            'operation.*.end_at'      => __('validation.attributes.end_at'),
            'operation.*.off_day'     => __('validation.attributes.off_day'),
        ];
    }
}
