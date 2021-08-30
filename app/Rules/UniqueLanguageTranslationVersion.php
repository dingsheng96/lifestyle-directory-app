<?php

namespace App\Rules;

use App\Models\Translation;
use Illuminate\Contracts\Validation\Rule;

class UniqueLanguageTranslationVersion implements Rule
{
    private $language;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($language)
    {
        $this->language = $language;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!empty($language)) {

            return Translation::where('language_id', $this->language->id)->where('version', $value)->exists();
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique');
    }
}
