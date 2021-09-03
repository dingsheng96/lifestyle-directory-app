<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Helpers\Status;
use App\Rules\PhoneFormat;
use App\Rules\ExistMerchant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CareerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::USER_TYPE_ADMIN)->check()
            && Gate::any(['career.create', 'career.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position'      =>  ['required'],
            'merchant'      =>  ['required', new ExistMerchant()],
            'status'        =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],
            'min_salary'    =>  ['required', 'numeric'],
            'max_salary'    =>  ['required', 'numeric', 'gte:min_salary'],
            'about'         =>  ['nullable'],
            'description'   =>  ['required'],
            'benefit'       =>  ['nullable'],
            'phone'         =>  ['nullable', new PhoneFormat],
            'email'         =>  ['nullable', 'email'],
            'whatsapp'      =>  ['nullable', new PhoneFormat],
            'website'       =>  ['nullable', 'url'],
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
            'position' => __('validation.attributes.job_title'),
            'merchant' => __('validation.attributes.company')
        ];
    }
}
