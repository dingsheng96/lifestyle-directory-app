<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\MerchantService;
use App\DataTables\Merchant\BranchDataTable;
use App\Http\Requests\Merchant\BranchRequest;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BranchDataTable $dataTable)
    {
        return $dataTable->render('merchant.branch.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD;

        $social_media = (new Misc())->getSocialMediaKeys();

        return view('merchant.branch.create', compact('max_files', 'social_media'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.branch', 1));
        $status     =   'fail';

        try {

            $merchant_service->setRequest($request)->storeBranch(Auth::user());

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:merchant_branch')
            ->causedBy(Auth::user())
            ->performedOn(new User())
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.branches.index')
            ])
            ->sendJson()
            : redirect()->route('merchant.branches.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(User $branch)
    {
        $branch->load([
            'branchDetail', 'address.city', 'media', 'operationHours', 'userSocialMedia'
        ]);

        $image_and_thumbnail = collect($branch->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $social_media = (new Misc())->getSocialMediaKeys();

        return view('merchant.branch.show', compact('branch', 'image_and_thumbnail', 'social_media'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(User $branch)
    {
        $branch->load([
            'branchDetail', 'address.city', 'media', 'operationHours', 'userSocialMedia'
        ]);

        $image_and_thumbnail = collect($branch->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - (clone $image_and_thumbnail)->count();

        $social_media = (new Misc())->getSocialMediaKeys();

        return view('merchant.branch.edit', compact('branch', 'max_files', 'image_and_thumbnail', 'social_media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, User $branch, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.branch', 1));
        $status     =   'fail';

        try {

            $branch->load(['media', 'address', 'branchDetail']);

            $merchant_service->setModel($branch)->setRequest($request)->storeBranch(Auth::user());

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:merchant_branch')
            ->causedBy(Auth::user())
            ->performedOn($branch)
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.branches.index')
            ])
            ->sendJson()
            : redirect()->route('merchant.branches.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $branch)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.branch', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        $branch->delete();

        activity()->useLog('merchant:merchant_branch')
            ->causedBy(Auth::user())
            ->performedOn($branch)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.branches.index')
            ])
            ->sendJson();
    }
}
