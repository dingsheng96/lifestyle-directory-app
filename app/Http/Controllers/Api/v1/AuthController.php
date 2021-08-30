<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Support\Services\MemberService;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $status     =   'success';
        $message    =   Message::instance()->login();
        $data       =   [];

        $user = User::with(['media'])->member()
            ->where('mobile_no', (new Misc())->phoneStoreFormat($request->get('phone')))
            ->first();

        $user->revokeTokens();

        $data = (new LoginResource($user))->toArray($request);

        activity()->useLog('api:auth')
            ->causedByAnonymous()
            ->performedOn($user)
            ->withProperties($request->all())
            ->log($user->name . ' ' . $message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate.' . $status)
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
            ->withProperties($request->all())
            ->log($user->name . ' ' . strtolower($message));

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }

    public function register(RegisterRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   Message::instance()->register($status);

        try {

            $member_service->setRequest($request)->store();

            $status     = 'success';
            $message    =   Message::instance()->register($status);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:auth')
            ->causedByAnonymous()
            ->performedOn(new User())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.create.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
