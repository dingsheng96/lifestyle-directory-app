<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BranchVisitorHistory extends Pivot
{
    use SoftDeletes;

    protected $table = 'branch_visitor_histories';

    protected $fillable = [
        'branch_id', 'visitor_id'
    ];
}
