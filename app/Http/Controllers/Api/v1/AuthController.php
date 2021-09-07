<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Misc;
use App\Helpers\Response;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Support\Services\MemberService;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request, MemberService $member_service)
    {
        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        $user = User::with(['media'])->member()
            ->where('mobile_no', (new Misc())->phoneStoreFormat($request->get('phone')))
            ->first();

        $user = $member_service->setModel($user)->setRequest($request)->linkDevice()->getModel();

        $user->load([
            'deviceSettings' => function ($query) {
                $query->wherePivot('status', UserDevice::STATUS_ACTIVE);
            }
        ]);

        $user->revokeTokens();

        $data = (new LoginResource($user))->toArray($request);

        activity()->useLog('api:login')
            ->causedByAnonymous()
            ->performedOn($user)
            ->withProperties($request->except('password'))
            ->log($user->name . ' login');

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }

    public function register(RegisterRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'success';
        $message    =   'Ok';

        try {

            $user = $member_service->setRequest($request)->store()->getModel();

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $status = 'fail';
            $message = $ex->getMessage();
        }

        activity()->useLog('api:register')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.create.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }

    public function logout(Request $request)
    {
        $status     =   'success';
        $message    =   'Ok';
        $user       =   $request->user();

        $user->deviceSettings()->active()->update([
            'user_id' => null
        ]);

        $user->token()->revoke();

        activity()->useLog('api:logout')
            ->causedBy($user)
            ->log($user->name . ' logout');

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.authenticate.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
