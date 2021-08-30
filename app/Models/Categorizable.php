<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Categorizable extends MorphPivot
{
    protected $table = 'categorizables';

    protected $fillable = [
        'category_id', 'categorizable_type', 'categorizable_id'
    ];
}
