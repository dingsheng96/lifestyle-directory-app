<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\AdminService;
use App\Http\Requests\Admin\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard(User::USER_TYPE_ADMIN)->user();

        return view('admin.profile', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request, AdminService $admin_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.profile'));
        $status     =   'fail';
        $user       =   Auth::guard(User::USER_TYPE_ADMIN)->user();

        try {

            $admin_service->setModel($user)->setRequest($request)->store();

            $status  =  'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message =  Message::instance()->format($action, $module, $status);

        activity()->useLog('admin:profile')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return redirect()->route('admin.profile.index')->with($status, $message);
    }
}
