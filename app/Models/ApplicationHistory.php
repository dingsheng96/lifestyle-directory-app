<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'application_histories';

    protected $fillable = [
        'user_id', 'status', 'remarks'
    ];

    // Constants
    const APPLICATION_STATUS_APPROVED   =   'approved';
    const APPLICATION_STATUS_PENDING    =   'pending';
    const APPLICATION_STATUS_REJECTED   =   'rejected';

    // Relationships
    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
