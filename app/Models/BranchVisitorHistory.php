<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchVisitorHistory extends Model
{
    use SoftDeletes;

    protected $table = 'branch_visitor_histories';

    protected $fillable = [
        'branch_id', 'visitor_id', 'visit_count'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }

    public function visitor()
    {
        return $this->belongsTo(User::class, 'visitor_id', 'id');
    }
}
