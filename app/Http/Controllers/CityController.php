<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\CountryStateService;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:locale.read'])->only(['index', 'show']);
        $this->middleware(['can:locale.create'])->only(['create', 'store']);
        $this->middleware(['can:locale.update'])->only(['edit', 'update']);
        $this->middleware(['can:locale.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request, CountryState $country_state, CountryStateService $country_state_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.city', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $country_state_service->setModel($country_state)->setRequest($request)->storeCity();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn(new City())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('locale.country-states.edit', ['country_state' => $country_state->id])->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CountryState $country_state, City $city)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.city', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $city->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($city)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.city', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('locale.country-states.edit', ['country_state' => $country_state->id])
            ])
            ->sendJson();
    }
}
