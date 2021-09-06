<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Helpers\Response;
use App\Helpers\Geocoding;
use App\Models\CountryState;
use App\Http\Controllers\Controller;
use App\Http\Requests\Data\GeocodingRequest;

class DataController extends Controller
{
    public function getCityFromCountryState(CountryState $country_state)
    {
        $status = 'success';

        $cities = City::where('country_state_id', $country_state->id)->orderBy('name')->get()->toArray();

        return Response::instance()
            ->withStatus($status)
            ->withData($cities)
            ->sendJson();
    }

    public function getLocationCoordinates(GeocodingRequest $request)
    {
        $status = 'success';
        $data   = [];
        $message = 'Ok';

        try {

            $geocoder = (new Geocoding())->setStreetAddress($request->get('address_1'), $request->get('address_2'))
                ->setPostCode($request->get('postcode'))
                ->setCity($request->get('city'))
                ->setCountryState($request->get('country_state'))
                ->getCoordinatesForAddress();

            $data = [
                'latitude' => $geocoder['lat'],
                'longitude' => $geocoder['lng']
            ];
        } catch (\Exception | \Error $e) {

            $message = $e->getMessage();
            $status = 'fail';
        }

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }
}
