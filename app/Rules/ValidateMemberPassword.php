<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class ValidateMemberPassword implements Rule
{
    public $column, $data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $column = 'id', string $data)
    {
        $this->column   =   $column;
        $this->data     =   $data;
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
        $user =  User::member()
            ->active()->where($this->column, $this->data)
            ->whereNull('deleted_at')->first();

        return $user && Hash::check($value, $user->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.password');
    }
}
