<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Media;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Helpers\FileManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class MediaController extends Controller
{
    public function destroy(Media $medium)
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
