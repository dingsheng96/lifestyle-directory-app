<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use App\Http\Controllers\Controller;

class DataController extends Controller
{


    public function getCityFromCountryState(CountryState $country_state)
    {
        $action = Permission::ACTION_READ;
        $status = 'success';

        $cities = City::where('country_state_id', $country_state->id)->orderBy('name')->get()->toArray();

        return Response::instance()
            ->withStatusCode('modules.city', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage()
            ->withData($cities)
            ->sendJson();
    }
}
