<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Admin\CityDataTable;
use App\Support\Services\CountryStateService;
use App\DataTables\Admin\CountryStateDataTable;
use App\Http\Requests\Admin\CountryStateRequest;

class CountryStateController extends Controller
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
    public function index(CountryStateDataTable $dataTable)
    {
        return $dataTable->render('admin.locale.country_state.index');
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

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
        }

        activity()->useLog('admin:country_state')
            ->causedBy(Auth::user())
            ->performedOn(new CountryState())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.locales.country-states.index')->with($status, $message);
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
        return $dataTable->with(['country_state_id' => $country_state->id])->render('admin.locale.country_state.edit', compact('country_state'));
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

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('admin:country_state')
            ->causedBy(Auth::user())
            ->performedOn($country_state)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.locales.country-states.index')->with($status, $message);
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

        activity()->useLog('admin:country_state')
            ->causedBy(Auth::user())
            ->performedOn($country_state)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.locales.country-states.index')
            ])
            ->sendJson();
    }
}
