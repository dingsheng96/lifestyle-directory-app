<?php

namespace App\Helpers;

use App\Models\City;
use GuzzleHttp\Client;
use App\Models\CountryState;
use Spatie\Geocoder\Geocoder;

class Geocoding
{
    private $street_address, $postcode, $city, $country_state;

    public function setStreetAddress(string $address_1, string $address_2 = null)
    {
        $this->street_address = $address_1;
        $this->street_address .= ',' . $address_2;

        return $this;
    }

    public function setPostCode(string $postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function setCity(int $city)
    {
        $get_city = City::findOrFail($city);

        $this->city = $get_city->name;

        return $this;
    }

    public function setCountryState(int $country_state)
    {
        $get_country_state = CountryState::findOrFail($country_state);

        $this->country_state = $get_country_state->name;

        return $this;
    }

    public function getCoordinatesForAddress(): array
    {
        $address = $this->street_address . ',' . $this->postcode . ',' . $this->city . ',' . $this->country_state;

        return (new Geocoder(new Client()))
            ->setApiKey(config('geocoder.key'))
            ->setCountry(config('geocoder.country', 'MY'))
            ->setLanguage(config('geocoder.language'))
            ->getCoordinatesForAddress($address);
    }
}
