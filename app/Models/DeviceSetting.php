<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSetting extends Model
{
    use SoftDeletes;

    protected $table = 'device_settings';

    protected $fillable = [
        'user_id', 'device_id', 'status',
        'push_messaging_provider', 'push_messaging_token',
        'enable_push_messaging', 'enable_notification_sound'
    ];

    protected $casts = [
        'enable_push_messaging' => 'boolean',
        'enable_notification_sound' => 'boolean'
    ];

    // Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
