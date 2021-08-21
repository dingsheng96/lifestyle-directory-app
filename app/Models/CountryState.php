<?php

namespace App\Models;

use App\Models\City;
use App\Models\Address;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryState extends Model
{
    use SoftDeletes;

    protected $table = 'country_states';

    protected $fillable = ['name'];

    // Relationships
    public function cities()
    {
        return $this->hasMany(City::class, 'country_state_id', 'id');
    }

    public function addresses()
    {
        return $this->hasManyThrough(Address::class, City::class, 'country_state_id', 'city_id', 'id', 'id');
    }
}
