<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'main_branch_id', 'sub_branch_id', 'status'
    ];

    // Constants
    const STATUS_PUBLISH    =   'publish';
    const STATUS_DRAFT      =   'draft';

    // Relationships
    public function mainBranch()
    {
        return $this->belongsTo(User::class, 'main_branch_id', 'user_id');
    }

    public function subBranch()
    {
        return $this->belongsTo(User::class, 'sub_branch_id', 'user_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISH);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
}
