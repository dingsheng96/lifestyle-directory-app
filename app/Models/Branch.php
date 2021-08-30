<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Branch extends Pivot
{
    use SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'main_branch_id', 'sub_branch_id', 'status'
    ];
}
