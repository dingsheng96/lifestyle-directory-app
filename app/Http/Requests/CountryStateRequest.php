<?php

namespace App\Http\Requests;

use App\Models\CountryState;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CountryStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('web')->check()
            && Gate::any(['locale.create', 'locale.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!empty($this->route('country_state'))) {
            return [
                'name' => [
                    Rule::requiredIf(empty($this->file('file'))),
                    'nullable',
                    Rule::unique(CountryState::class, 'name')
                        ->ignore($this->route('country_state'), 'id')
                        ->whereNull('deleted_at')
                ]
            ];
        }

        return [
            'create.name' => [
                Rule::requiredIf(!$this->hasFile('create.file')),
                'nullable',
                Rule::unique(CountryState::class, 'name')
                    ->whereNull('deleted_at')
            ],
            'create.file' => [
                Rule::requiredIf(empty($this->get('create')['name'])),
                'nullable',
                'file',
                'mimes:txt,csv,xlsx',
                'max:20000'
            ],
            'create.withCity' => [
                'nullable'
            ]
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
            'create.name'  =>  __('validation.attributes.name'),
            'create.file'  =>  __('validation.attributes.file'),
        ];
    }
}
