<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ExistWishlistMerchant implements Rule
{
    public $column, $valid, $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user, string $column = 'id', bool $valid = true)
    {
        $this->column   = $column;
        $this->valid    = $valid;
        $this->user     = $user;
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
        return User::merchant()
            ->when($this->valid, function ($query) {
                $query->active()->approvedApplication();
            })
            ->where($this->column, $value)
            ->orWhere(function ($query) {
                $query->whereHas('favouriteBy', function ($query) {
                    $query->where('id', $this->user->id);
                });
            })
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.exists');
    }
}
