<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Career extends Model
{
    use SoftDeletes;

    protected $table = 'careers';

    protected $fillable = [
        'branch_id', 'position', 'description', 'min_salary',
        'max_salary', 'show_salary', 'status'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }
}
