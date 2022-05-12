<?php

namespace App\Http\Requests\Merchant;

use App\Models\City;
use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Status;
use App\Models\Category;
use App\Rules\PhoneFormat;
use App\Models\BranchDetail;
use App\Models\CountryState;
use App\Rules\UniqueMerchant;
use App\Models\UserSocialMedia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
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
        return Auth::guard(User::USER_TYPE_MERCHANT)->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'              =>  ['required', 'min:3', 'max:255'],
            'phone'             =>  ['required', new PhoneFormat],
            'email'             =>  ['required', 'email', new UniqueMerchant('email', $this->route('merchant'))],
            'listing_status'    =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],

            'description'       =>  ['nullable', 'string'],
            'services'          =>  ['nullable', 'string'],
            'career_desc'       =>  ['nullable', 'string'],

            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  ['required', Rule::exists(CountryState::class, 'id')],
            'city'              =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $this->get('country_state'))],

            'reg_no'            =>  ['required', Rule::unique(BranchDetail::class, 'reg_no')->ignore(optional($this->route('merchant'))->id, 'branch_id')->whereNull('deleted_at')],
            'pic_name'          =>  ['required'],
            'pic_phone'         =>  ['required', new PhoneFormat],
            'pic_email'         =>  ['required', 'email'],

            'logo'              =>  ['nullable', 'image', 'max:2000', 'mimes:jpg,jpeg,png'],
            'ssm_cert'          =>  ['nullable', 'file', 'max:2000', 'mimes:pdf'],
            'files'             =>  ['nullable'],
            'files.*'           =>  ['image', 'mimes:jpg,jpeg,png'],
            'thumbnail'         =>  ['nullable', 'exists:' . Media::class . ',id'],

            'operation.*'               =>  ['required', 'array'],
            'operation.*.start_from'    =>  ['required', 'date_format:H:i'],
            'operation.*.end_at'        =>  ['required', 'date_format:H:i'],
            'operation.*.off_day'       =>  ['nullable', 'in:on'],
        ];

        $social_media_rules = [];

        foreach ((new Misc())->getSocialMediaKeys() as $media_key => $media_text) {

            if ($media_key == UserSocialMedia::SOCIAL_MEDIA_KEY_WHATSAPP) {
                $social_media_rules[$media_key] = ['nullable', new PhoneFormat];
                continue;
            }

            $social_media_rules[$media_key] = ['nullable', 'url'];
        }

        return array_merge($rules, $social_media_rules);
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
