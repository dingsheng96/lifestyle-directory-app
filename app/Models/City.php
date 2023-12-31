<?php

namespace App\Models;

use App\Models\Country;
use App\Models\CountryState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $table = 'cities';

    protected $fillable = ['name', 'country_state_id'];

    // Relationships
    public function countryState()
    {
        return $this->belongsTo(CountryState::class, 'country_state_id', 'id');
    }

    // Attributes
    public function getCountryStateNameAttribute()
    {
        return $this->countryState->name ?? '';
    }
}
