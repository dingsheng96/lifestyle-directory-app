<?php

namespace App\Http\Controllers\Admin;

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
use App\DataTables\Admin\BranchDataTable;
use App\Support\Services\MerchantService;
use App\DataTables\Admin\MerchantDataTable;
use App\Http\Requests\Admin\MerchantRequest;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:merchant.read'])->only(['index', 'show']);
        $this->middleware(['can:merchant.create'])->only(['create', 'store']);
        $this->middleware(['can:merchant.update'])->only(['edit', 'update']);
        $this->middleware(['can:merchant.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MerchantDataTable $dataTable)
    {
        return $dataTable->render('admin.merchant.index');
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

        return view('admin.merchant.create', compact('max_files', 'social_media'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant_service->setRequest($request)->storeMainMerchant();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('admin:merchant')
            ->causedBy(Auth::user())
            ->performedOn(new User())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('admin.merchants.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $merchant, BranchDataTable $dataTable)
    {
        $merchant->load([
            'branchDetail', 'address.city', 'media', 'subBranches',
            'categories', 'operationHours', 'userSocialMedia'
        ]);

        $image_and_thumbnail = collect($merchant->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        return $dataTable->with(['merchant' => $merchant, 'view_only' => true])->render('admin.merchant.show', compact('merchant', 'image_and_thumbnail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant, BranchDataTable $dataTable)
    {
        $merchant->load([
            'branchDetail', 'address.city', 'media', 'categories', 'userSocialMedia',
            'subBranches' => function ($query) {
                $query->orderByDesc('created_at');
            },
            'operationHours' => function ($query) {
                $query->orderBy('days_of_week');
            }
        ]);

        $image_and_thumbnail = collect($merchant->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - (clone $image_and_thumbnail)->count();

        $social_media = (new Misc())->getSocialMediaKeys();

        return $dataTable->with(['merchant' => $merchant])->render('admin.merchant.edit', compact('merchant', 'image_and_thumbnail', 'max_files', 'social_media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantRequest $request, User $merchant, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $merchant->load(['media', 'address', 'branchDetail', 'categories', 'userSocialMedia']);

            $merchant_service->setModel($merchant)->setRequest($request)->storeMainMerchant();

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('admin:merchant')
            ->causedBy(Auth::user())
            ->performedOn($merchant)
            ->withProperties($request->all())
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.merchants.index')
            ])
            ->sendJson()
            : redirect()->route('admin.merchants.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $merchant)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        $merchant->delete();

        activity()->useLog('admin:merchant')
            ->causedBy(Auth::user())
            ->performedOn($merchant)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('admin.merchants.index')
            ])
            ->sendJson();
    }
}
