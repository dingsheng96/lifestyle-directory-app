<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Media;
use App\Helpers\Status;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MerchantRequest;
use App\Support\Services\MerchantService;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $merchant)
    {
        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD;

        return view('merchant.branch.create', compact('max_files', 'merchant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request, User $merchant, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('labels.branch', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant_service->setRequest($request)->storeBranch($merchant);

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn(new User())
            ->withProperties($request->all())
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchants.edit', ['merchant' => $merchant->id])
            ])
            ->sendJson()
            : redirect()->route('merchants.edit', ['merchant' => $merchant->id])->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $merchant, User $branch)
    {
        $branch->load([
            'branchDetail', 'address.city', 'media', 'operationHours'
        ]);

        $image_and_thumbnail = collect($branch->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        return view('merchant.branch.show', compact('merchant', 'branch', 'image_and_thumbnail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant, User $branch)
    {
        $branch->load([
            'branchDetail', 'address.city', 'media', 'operationHours'
        ]);

        $image_and_thumbnail = collect($branch->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - (clone $image_and_thumbnail)->count();

        return view('merchant.branch.edit', compact('merchant', 'branch', 'max_files', 'image_and_thumbnail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantRequest $request, User $merchant, User $branch, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('labels.branch', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $branch->load(['media', 'address', 'branchDetail']);

            $merchant_service->setModel($branch)->setRequest($request)->storeBranch($merchant);

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($branch)
            ->withProperties($request->all())
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchants.edit', ['merchant' => $merchant->id])
            ])
            ->sendJson()
            : redirect()->route('merchants.edit', ['merchant' => $merchant->id])->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $merchant, User $branch)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.branch', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        $branch->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($branch)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchants.edit', ['merchant' => $merchant->id])
            ])
            ->sendJson();
    }
}
