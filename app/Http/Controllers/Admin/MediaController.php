<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    public function __invoke(Media $medium)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   'media';
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, $status);

        (new FileManager())->removeFile($medium->file_path);

        $medium->delete();

        activity()->useLog('admin:media')
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
