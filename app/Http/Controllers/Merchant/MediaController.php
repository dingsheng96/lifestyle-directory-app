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
use Illuminate\Database\Eloquent\Builder;
use App\DataTables\Merchant\MediaDataTable;
use App\Http\Requests\Merchant\MediaRequest;

class MediaController extends Controller
{
    public function index()
    {
        $user = Auth::user()->loadCount([
            'media' => function ($query) {
                $query->where(function ($query) {
                    $query->thumbnail()
                        ->orWhere(function ($query) {
                            $query->image();
                        });
                });
            }
        ])->load([
            'media' => function ($query) {
                $query->where(function ($query) {
                    $query->thumbnail()
                        ->orWhere(function ($query) {
                            $query->image();
                        });
                });
            }
        ]);

        $max_files = Media::MAX_BRANCH_IMAGE_UPLOAD - $user->media_count;

        return view('merchant.media.index', compact('max_files', 'user'));
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

    public function reorder(Request $request)
    {
        $request->validate([
            'media' => ['required', 'array'],
            'media.*' => ['integer'],
        ]);

        foreach ($request->get('media') as $index => $id) {
            $medium = Media::where('id', $id)->first();
            $medium->position = $index + 1;
            $medium->saveQuietly();
        }

        switch ($request->get('parent_type')) {
            case 'merchant':
                $class = User::class;
                break;
        }

        $positions = Media::query()
            ->whereHasMorph('mediable', $class, function (Builder $query) use ($request) {
                $query->where('id', $request->get('parent_id'));
            })
            ->pluck('position', 'id');


        return Response::instance()
            ->withStatus('success')
            ->withMessage()
            ->withData(compact('positions'))
            ->sendJson();
    }
}
