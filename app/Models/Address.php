<?php

namespace App\Models;

use App\Models\City;
use App\Models\CountryState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'addressable_type', 'addressable_id', 'address_1',
        'address_2', 'postcode', 'city_id', 'latitude', 'longitude'
    ];

    // Relationships
    public function addressable()
    {
        return $this->morphTo();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function countryState()
    {
        return $this->hasOneThrough(CountryState::class, City::class, 'id', 'id', 'city_id', 'country_state_id');
    }

    public function getFullAddressAttribute()
    {
        $full_address  =    $this->address_1 . ', ';
        $full_address  .=   $this->address_2 . ', ';
        $full_address  .=   $this->postcode . ', ';
        $full_address  .=   $this->city->name . ', ';
        $full_address  .=   $this->country_state_name . ', ';
        $full_address  .=   'Malaysia';

        return $full_address;
    }

    public function getLocationCityStateAttribute()
    {
        return $this->city->name . ', ' . $this->countryState->name;
    }
}
