<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Helpers\Status;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::USER_TYPE_ADMIN)->check()
            && Gate::any(['category.create', 'category.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          =>  ['required', Rule::unique(Category::class, 'name')->ignore($this->route('category'), 'id')->whereNull('deleted_at')],
            'image'         =>  [Rule::requiredIf(empty($this->route('category'))), 'nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2000'],
            'description'   =>  ['nullable'],
            'status'        =>  ['required', Rule::in(array_keys((new Status())->publishStatus()))]
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
