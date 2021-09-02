<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\RoleService;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:role.read'])->only(['index', 'show']);
        $this->middleware(['can:role.create'])->only(['create', 'store']);
        $this->middleware(['can:role.update'])->only(['edit', 'update']);
        $this->middleware(['can:role.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request, RoleService $role_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.role', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $role_service->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn(new Role())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('roles.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role->load([
            'permissions' => function ($query) {
                $query->select('id');
            }
        ]);

        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $role->load([
            'permissions' => function ($query) {
                $query->select('id');
            }
        ]);

        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role, RoleService $role_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.role', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $role_service->setModel($role)->setRequest($request)->store();

            $status     =   'success';
            $message    =    Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('roles.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.role', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        $role->syncPermissions([]);
        $role->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($role)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('roles.index')
            ])
            ->sendJson();
    }
}
