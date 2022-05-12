<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Config extends Model
{
    use HasFactory;

    protected $table = 'configs';

    protected $fillable = [
        'value'
    ];

    // Constants
    const SEARCH_RADIUS_IN_KM = 'search_radius_in_km';
    const REVIEW_IDLE_PERIOD_IN_DAYS = 'review_idle_period_in_days';

    // Scopes
    public function scopeSearchRadiusInKm($query)
    {
        return $query->where('key', self::SEARCH_RADIUS_IN_KM);
    }

    public function scopeReviewIdlePeriodInDays($query)
    {
        return $query->where('key', self::REVIEW_IDLE_PERIOD_IN_DAYS);
    }
}
