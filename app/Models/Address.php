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

    // Constants
    const SEARCH_RADIUS_IN_KM = 5;

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

    // Scopes
    public function scopeFilterByCoordinates($query, $latitude, $longitude)
    {
        return $query->selectRaw(
            "(6371 * acos(cos(radians(?)) * cos( radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin( radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        )->having("distance", "<=", self::SEARCH_RADIUS_IN_KM);
    }

    public function scopeGetDistanceByCoordinates($query, $latitude, $longitude)
    {
        return $query->selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos( radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin( radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        );
    }

    // Attributes
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

    public function getFormattedDistanceAttribute()
    {
        if ($this->distance < 0) {

            return (number_format($this->distance, 3) * 1000) . 'm';
        }

        return number_format($this->distance, 1) . 'km';
    }
}
