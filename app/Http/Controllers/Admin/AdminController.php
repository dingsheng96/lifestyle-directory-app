<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\AdminService;
use App\DataTables\Admin\AdminDataTable;
use App\Http\Requests\Admin\AdminRequest;
use App\DataTables\Admin\ReferralDataTable;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:admin.read'])->only(['index', 'show']);
        $this->middleware(['can:admin.create'])->only(['create', 'store']);
        $this->middleware(['can:admin.update'])->only(['edit', 'update']);
        $this->middleware(['can:admin.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDataTable $dataTable)
    {
        return $dataTable->render('admin.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request, AdminService $admin_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $status     =   'fail';

        try {

            $admin_service->setRequest($request)->store()->assignRole();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('admin:admin')
            ->causedBy(Auth::user())
            ->performedOn(new User())
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return redirect()->route('admin.admins.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $admin, ReferralDataTable $dataTable)
    {
        abort_if(!Auth::user()->is_super_admin && $admin->is_super_admin || $admin->id == Auth::id(), 403);

        $admin->loadCount('referredBy');

        return $dataTable->with(compact('admin'))->render('admin.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $admin)
    {
        abort_if(!Auth::user()->is_super_admin && $admin->is_super_admin || $admin->id == Auth::id(), 403);

        return view('admin.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, User $admin, AdminService $admin_service)
    {
        abort_if(!Auth::user()->is_super_admin && $admin->is_super_admin || $admin->id == Auth::id(), 403);

        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $admin_service->setModel($admin)->setRequest($request)->store()->assignRole();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('admin:admin')
            ->causedBy(Auth::user())
            ->performedOn(new User())
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return redirect()->route('admin.admins.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $admin)
    {
        abort_if(!Auth::user()->is_super_admin && $admin->is_super_admin || $admin->id == Auth::id(), 403);

        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.admin', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $admin->delete();

        activity()->useLog('admin:admin')
            ->causedBy(Auth::user())
            ->performedOn($admin)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.admins.index')
            ])
            ->sendJson();
    }
}
