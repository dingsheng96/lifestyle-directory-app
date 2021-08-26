<?php

namespace App\Http\Requests;

use App\Helpers\Status;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['banner.create', 'banner.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         =>  ['required'],
            'status'        =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))],
            'image'         =>  [Rule::requiredIf(empty($this->route('banner'))), 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2000'],
            'description'   =>  ['nullable']
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
        return [];
    }
}
