<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use App\DataTables\CityDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\CountryStateDataTable;
use App\Http\Requests\CountryStateRequest;
use App\Support\Services\CountryStateService;

class CountryStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CountryStateDataTable $dataTable)
    {
        return $dataTable->render('locale.country_state.index');
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
    public function store(CountryStateRequest $request, CountryStateService $country_state_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.country_state', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $country_state_service->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new CountryState())
                ->withProperties($request->all())
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new CountryState())
                ->withProperties($request->all())
                ->log($e->getMessage());
        }

        return redirect()->route('locale.country-states.index')->with($status, $message);
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
    public function edit(CountryState $country_state, CityDataTable $dataTable)
    {
        return $dataTable->with(['country_state_id' => $country_state->id])->render('locale.country_state.edit', compact('country_state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CountryStateRequest $request, CountryState $country_state, CountryStateService $country_state_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.country_state', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $country_state_service->setModel($country_state)->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country_state)
                ->withProperties($request->all())
                ->log($message);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($country_state)
                ->withProperties($request->all())
                ->log($e->getMessage());
        }

        return redirect()->route('locale.country-states.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CountryState $country_state)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.country_state', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $country_state->cities()->delete();
        $country_state->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($country_state)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.country_state', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('locale.country-states.index')
            ])
            ->sendJson();
    }
}
