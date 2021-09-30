<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\MerchantService;
use App\DataTables\Admin\ApplicationDataTable;
use App\Http\Requests\Admin\ApplicationRequest;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ApplicationDataTable $dataTable)
    {
        return $dataTable->render('admin.application.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $application
     * @return \Illuminate\Http\Response
     */
    public function show(User $application)
    {
        $application->load([
            'address', 'branchDetail', 'media', 'referrals'
        ]);

        return view('admin.application.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(User $application)
    {
        $application->load([
            'address', 'branchDetail', 'media', 'referrals'
        ]);

        $status_approve = User::APPLICATION_STATUS_APPROVED;
        $status_reject  = User::APPLICATION_STATUS_REJECTED;

        return view('admin.application.edit', compact('application', 'status_approve', 'status_reject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $application
     * @return \Illuminate\Http\Response
     */
    public function update(ApplicationRequest $request, User $application, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action             =   'application';
        $module             =   strtolower(trans_choice('modules.application', 1));
        $status             =   'fail';
        $application_status =   User::APPLICATION_STATUS_REJECTED;

        try {

            $application_status = $request->get('application_status');

            if ($application_status == User::APPLICATION_STATUS_APPROVED) {

                $application_status = User::APPLICATION_STATUS_APPROVED;
            }

            $merchant_service->setModel($application)->setRequest($request)->setApplicationStatus($application_status, $request->get('remarks', NULL));

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $application_status);

        activity()->useLog('admin:application')
            ->causedBy(Auth::user())
            ->performedOn($application)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.applications.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $application)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.application', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $application->delete($application);

        activity()->useLog('admin:application')
            ->causedBy(Auth::user())
            ->performedOn($application)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.applications.index')
            ])
            ->sendJson();
    }
}
