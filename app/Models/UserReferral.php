<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserReferral extends Pivot
{
    protected $table = 'user_referral';
}
