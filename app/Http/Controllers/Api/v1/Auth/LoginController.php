<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Http\Requests\Api\v1\Auth\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $status     =   'success';
        $message    =   Message::instance()->login();
        $data       =   [];

        $user = User::with(['media'])->member()
            ->where('mobile_no', (new Misc())->phoneStoreFormat($request->get('mobile_no')))
            ->first();

        $user->revokeTokens();

        $data = (new LoginResource($user))->toArray($request);

        activity()->useLog('api:auth')
            ->causedByAnonymous()
            ->performedOn($user)
            ->withProperties($request->all())
            ->log($user->name . ' ' . $message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }

    public function logout(Request $request)
    {
        $status     =   'success';
        $message    =   Message::instance()->logout();

        $user = $request->user();

        $user->token()->revoke();

        activity()->useLog('api:auth')
            ->causedBy($user)
            ->log($user->name . ' ' . strtolower($message));

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
