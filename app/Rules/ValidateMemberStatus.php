<?php

namespace App\Rules;

use App\Models\User;
use App\Helpers\Misc;
use Illuminate\Contracts\Validation\Rule;

class ValidateMemberStatus implements Rule
{
    public $column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $column = 'id')
    {
        $this->column = $column;
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
        return User::member()->active()
            ->when($this->column == 'mobile_no', function ($query) use ($value) {
                $query->where($this->column, (new Misc())->phoneStoreFormat($value));
            })->when($this->column != 'mobile_no', function ($query) use ($value) {
                $query->where($this->column, $value);
            })
            ->whereNull('deleted_at')->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.custom.inactive', ['attribute' => __('validation.attributes.account')]);
    }
}
