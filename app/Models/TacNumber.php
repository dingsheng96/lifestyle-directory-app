<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TacNumber extends Model
{
    protected $table = 'tac_numbers';

    protected $fillable = [
        'purpose', 'mobile_no', 'tac', 'status', 'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];
}
