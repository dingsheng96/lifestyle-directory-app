<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Models\Language;
use App\Models\Translation;
use App\Helpers\FileManager;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueLanguageTranslationVersion;

class LanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::USER_TYPE_ADMIN)->check()
            && Gate::any(['locale.create', 'locale.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $count = 0;

        if (strtolower($this->getMethod()) == 'put') {

            $language =  $this->route('language')->loadCount('translations');

            $count = $language->translations_count;
        }

        return [
            'name'              =>  ['required'],
            'code'              =>  ['required', Rule::unique(Language::class, 'code')->ignore($this->route('language'), 'id')->whereNull('deleted_at')],
            'new_version'       =>  [Rule::requiredIf(!empty($this->get('file'))), 'nullable', new UniqueLanguageTranslationVersion($this->route('language'))],
            'file'              =>  [Rule::requiredIf(!empty($this->get('new_version'))), 'nullable', 'max:2000', 'mimes:' . implode(',', array_keys((new FileManager())->reader))],
            'current_version'   =>  [Rule::requiredIf(!empty($this->route('language')) && $count > 0), 'nullable', Rule::exists(Translation::class, 'version')->whereNull('deleted_at')]
        ];
    }
}
