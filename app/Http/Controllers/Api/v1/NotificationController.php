<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserNotificationResource;
use App\Http\Requests\Api\v1\Notification\NotificationListRequest;
use App\Http\Requests\Api\v1\Notification\ShowNotificationRequest;

class NotificationController extends Controller
{
    public function index(NotificationListRequest $request)
    {
        $status =   'success';
        $user   =   $request->user();

        $notifications = UserNotification::where('user_id', $user->id)->orderByDesc('created_at')->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.notification', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(UserNotificationResource::collection($notifications)->toArray($request))
            ->sendJson();
    }

    public function show(ShowNotificationRequest $request)
    {
        $user   =   $request->user();

        $notification = UserNotification::where('user_id', $user->id)->where('id', $request->get('notification_id'))->firstOrFail();

        $notification->read_at = now();
        $notification->save();

        return view('webview.notification', compact('notification'));
    }
}
