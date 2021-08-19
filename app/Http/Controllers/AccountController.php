<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccountRequest;
use App\Support\Facades\AccountFacade;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user()->load([
            'address',
            'userAdsQuotas' => function ($query) {
                $query->orderBy('product_id');
            },
            'userDetail' => function ($query) {
                $query->approvedDetails();
            },
            'userSubscriptions' => function ($query) {
                $query->with([
                    'userSubscriptionLogs' => function ($query) {
                        $query->orderByDesc('renewed_at');
                    }
                ])->active();
            }
        ]);

        $address = $user->address;
        $user_details = $user->userDetail;

        return view('account.' . Auth::user()->folder_name, compact('user', 'user_details', 'address'));
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
    public function store(AccountRequest $request)
    {
        DB::beginTransaction();

        $user       =   User::findOrFail(Auth::id());
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(__('labels.user_account'));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module, $status);

        try {

            $account = AccountFacade::setModel($user)->setRequest($request)->storeData()->getModel();

            DB::commit();

            if ($request->get('new_password')) {
                Auth::guard('web')->login($account);
            }

            $message =  Message::instance()->format($action, $module, 'success');
            $status  =  'success';

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($account)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('account.index')->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($user)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message);
        }
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
    public function destroy($id)
    {
        //
    }
}