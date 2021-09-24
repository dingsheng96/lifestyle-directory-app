<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceSetting extends Model
{
    use SoftDeletes;

    protected $table = 'device_settings';

    protected $fillable = [
        'user_id', 'device_id', 'device_os', 'push_messaging_token',
        'enable_push_messaging', 'enable_notification_sound'
    ];

    protected $casts = [
        'enable_push_messaging' => 'boolean',
        'enable_notification_sound' => 'boolean'
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, UserDevice::class, 'device_id', 'user_id', 'id', 'id');
    }
}
