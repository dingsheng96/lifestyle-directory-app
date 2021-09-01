<?php

namespace App\Models;

use Carbon\Carbon;
use App\Casts\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationHour extends Model
{
    use SoftDeletes;

    protected $table = 'operation_hours';

    protected $fillable = [
        'branch_id', 'days_of_week', 'day_off', 'start', 'end'
    ];

    protected $casts = [
        'start' => Time::class,
        'end' => Time::class,
        'day_off' => 'boolean'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }

    // Attributes
    public function setStartAttribute($value)
    {
        $this->attributes['start'] = Carbon::parse($value)->toTimeString();
    }

    public function setEndAttribute($value)
    {
        $this->attributes['end'] = Carbon::parse($value)->toTimeString();
    }
}
