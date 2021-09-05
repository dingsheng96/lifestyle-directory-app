<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Models\Media;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\MerchantService;
use App\DataTables\Merchant\MediaDataTable;
use App\Http\Requests\Merchant\MediaRequest;

class MediaController extends Controller
{
    public function index(MediaDataTable $dataTable)
    {
        $user = Auth::user()->loadCount([
            'media' => function ($query) {
                $query->where(function ($query) {
                    $query->thumbnail()->orWhere(function ($query) {
                        $query->image();
                    });
                });
            }
        ]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - $user->media_count;

        return $dataTable->render('merchant.media.index', compact('max_files'));
    }

    public function store(MediaRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('labels.image', 1));
        $status     =   'fail';

        try {

            $merchant_service->setModel(Auth::user())->setRequest($request)->storeImage();

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:media')
            ->causedBy(Auth::user())
            ->performedOn(new Media())
            ->withProperties($request->all())
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.media.index')
            ])
            ->sendJson()
            : redirect()->route('merchant.media.index')->with($status, $message);
    }

    public function update(Request $request, Media $medium, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('labels.image', 1));
        $status     =   'fail';

        try {

            $request->request->add(['thumbnail' => $medium->id]);

            $merchant_service->setModel(Auth::user())->setRequest($request)->setThumbnail();

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:media')
            ->causedBy(Auth::user())
            ->performedOn($medium)
            ->withProperties($request->all())
            ->log($message);

        return $request->ajax() ?
            Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('merchant.media.index')
            ])
            ->sendJson()
            : redirect()->route('merchant.media.index')->with($status, $message);
    }

    public function destroy(Media $medium)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   'media';
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        (new FileManager())->removeFile($medium->file_path);

        $medium->delete();

        activity()->useLog('merchant:media')
            ->causedBy(Auth::user())
            ->performedOn($medium)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => url()->previous()
            ])
            ->sendJson();
    }
}
