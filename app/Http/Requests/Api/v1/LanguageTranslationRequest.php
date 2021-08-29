<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Language;
use App\Http\Requests\Api\v1\BaseRequest;

class LanguageTranslationRequest extends BaseRequest
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
        $this->setModule('language')->setAction('index');

        return [
            'code'  => ['required', 'exists:' . Language::class . ',code']
        ];
    }
}
