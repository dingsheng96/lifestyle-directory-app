<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserDevice extends Pivot
{
    protected $table = 'user_device';

    protected $fillable = [
        'user_id', 'device_id', 'status'
    ];

    // Constants
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';
}
