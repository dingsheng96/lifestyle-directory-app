<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Helpers\Misc;
use App\Models\Media;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\MerchantService;
use App\Http\Requests\Merchant\MerchantRequest;

class MerchantController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $merchant)
    {
        $user = $merchant->load(['address', 'media', 'branchDetail', 'userSocialMedia'])->loadCount(['ratings']);

        $image_and_thumbnail = collect($user->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $social_media = (new Misc())->getSocialMediaKeys();

        return view('merchant.merchant.show', compact('user', 'social_media', 'image_and_thumbnail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $merchant)
    {
        $user = $merchant->load(['address', 'media', 'branchDetail', 'userSocialMedia'])->loadCount(['ratings']);

        $image_and_thumbnail = collect($user->media)->whereNotIn('type', [Media::TYPE_LOGO, Media::TYPE_SSM]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - (clone $image_and_thumbnail)->count();

        $social_media = (new Misc())->getSocialMediaKeys();

        return view('merchant.merchant.edit', compact('user', 'social_media', 'image_and_thumbnail', 'max_files'));
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
        $module     =   strtolower(trans('labels.main_hq'));
        $status     =   'fail';

        try {

            $merchant->load(['media', 'address', 'branchDetail']);

            $merchant_service->setModel($merchant)->setRequest($request)->store();

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:main_merchant')
            ->causedBy(Auth::user())
            ->performedOn($merchant)
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
