<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Models\Career;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\CareerService;
use App\DataTables\Merchant\CareerDataTable;
use App\Http\Requests\Merchant\CareerRequest;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CareerDataTable $dataTable)
    {
        return $dataTable->render('merchant.career.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchant = Auth::user()->load(['mainBranch', 'subBranches']);
        $merchants = collect([$merchant]);

        if ($merchant->is_main_merchant) {
            $merchants->merge($merchant->subBranches);
        }

        $merchants->filter(function ($item) {
            return !is_null($item);
        });

        return view('merchant.career.create', compact('merchants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CareerRequest $request, CareerService $career_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.career', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $career_service->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('merchant:career')
            ->causedBy(Auth::user())
            ->performedOn(new Career())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('merchant.careers.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function show(Career $career)
    {
        $career->load(['branch.address']);

        return view('merchant.career.show', compact('career'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function edit(Career $career)
    {
        $career->load(['branch.address']);

        $merchant = Auth::user()->load(['mainBranch', 'subBranches']);

        $merchants = collect([$merchant]);

        if ($merchant->is_main_merchant) {
            $merchants->merge($merchant->subBranches);
        }

        $merchants->filter(function ($item) {
            return !is_null($item);
        });

        return view('merchant.career.edit', compact('career', 'merchants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function update(CareerRequest $request, Career $career, CareerService $career_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.career', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $career_service->setModel($career)->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('merchant:career')
            ->causedBy(Auth::user())
            ->performedOn($career)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('merchant.careers.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function destroy(Career $career)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.career', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $career->delete();

        activity()->useLog('merchant:career')
            ->causedBy(Auth::user())
            ->performedOn($career)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.careers.index')
            ])
            ->sendJson();
    }
}
