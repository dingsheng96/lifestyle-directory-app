<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use SoftDeletes;

    // Constants
    const ROLE_SUPER_ADMIN  = 'Super Admin';

    protected $fillable = ['generate_referral'];

    protected $casts = [
        'generate_referral' => 'boolean'
    ];
}
