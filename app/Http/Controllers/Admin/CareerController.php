<?php

namespace App\Http\Controllers\Admin;

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
use App\DataTables\Admin\CareerDataTable;
use App\Http\Requests\Admin\CareerRequest;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CareerDataTable $dataTable)
    {
        return $dataTable->render('admin.career.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchants = User::merchant()->orderBy('name')->get();

        return view('admin.career.create', compact('merchants'));
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

        activity()->useLog('admin:career')
            ->causedBy(Auth::user())
            ->performedOn(new Career())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.careers.index')->with($status, $message);
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

        return view('admin.career.show', compact('career'));
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
        $merchants = User::merchant()->orderBy('name')->get();

        return view('admin.career.edit', compact('career', 'merchants'));
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

        activity()->useLog('admin:career')
            ->causedBy(Auth::user())
            ->performedOn($career)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.careers.index')->with($status, $message);
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

        activity()->useLog('admin:career')
            ->causedBy(Auth::user())
            ->performedOn($career)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.careers.index')
            ])
            ->sendJson();
    }
}
