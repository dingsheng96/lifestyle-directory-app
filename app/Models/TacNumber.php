<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class TacNumber extends Model
{
    protected $table = 'tac_numbers';

    protected $fillable = [
        'purpose', 'mobile_no', 'tac', 'status', 'verified_at', 'expired_at'
    ];

    protected $casts = [
        'verified_at'   => 'datetime',
        'expired_at'    => 'datetime'
    ];

    const PURPOSE_RESET_PASSWORD = 'reset_password';
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const ACTIVE_PERIOD_IN_MINUTES = 5;

    // Scopes
    public function scopeExpired($query)
    {
        return $query->where('expired_at', '<=', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where('expired_at', '>', now());
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    // Attributes
    public function setTacAttribute($value)
    {
        $this->attributes['tac'] = Hash::make($value);
    }
}
