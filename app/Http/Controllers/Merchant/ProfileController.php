<?php

namespace App\Http\Controllers\Merchant;

use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\MerchantService;
use App\Http\Requests\Merchant\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load(['address', 'media', 'branchDetail'])->loadCount(['ratings']);

        return view('merchant.profile', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.profile'));
        $status     =   'fail';
        $user       =   Auth::user();

        try {

            $merchant_service->setModel($user)->setRequest($request)->store();

            $status  =  'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message =  Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:profile')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return redirect()->route('merchant.profile.index')->with($status, $message);
    }
}
