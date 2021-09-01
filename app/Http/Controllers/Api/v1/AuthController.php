<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Message;
use App\Helpers\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use App\Support\Services\MemberService;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function preRegister(Request $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   'Ok';
        $data       =   [];

        try {

            $guest = $member_service->storeGuest()->getModel();

            $status =   'success';
            $data   =   (new LoginResource($guest))->toArray($request);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:pre_register')
            ->causedByAnonymous()
            ->performedOn(new User())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.guest', 'actions.create.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }

    public function login(LoginRequest $request, MemberService $member_service)
    {
        $status     =   'success';
        $message    =   Message::instance()->login();
        $data       =   [];

        $user = User::with(['media'])->member()
            ->where('mobile_no', (new Misc())->phoneStoreFormat($request->get('phone')))
            ->first();

        $user = $member_service->setModel($user)->setRequest($request)->changeActiveDevice()->getModel();

        $user->load([
            'deviceSettings' => function ($query) {
                return $query->active();
            }
        ]);

        $user->revokeTokens();

        $data = (new LoginResource($user))->toArray($request);

        activity()->useLog('api:login')
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

        activity()->useLog('api:logout')
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

            if (Auth::guard('api')->check()) {
                $member_service->setModel($request->user())->setRequest($request)->store();
            } else {
                $member_service->setRequest($request)->store();
            }

            $status     = 'success';
            $message    =   Message::instance()->register($status);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:register')
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
