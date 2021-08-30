<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Language;
use App\Http\Requests\Api\v1\BaseRequest;

class LanguageTranslationRequest extends BaseRequest
{
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
