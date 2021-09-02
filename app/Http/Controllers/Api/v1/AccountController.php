<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\DeviceSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Support\Services\MemberService;
use App\Http\Requests\Api\v1\Account\DeviceSettingRequest;
use App\Http\Requests\Api\v1\Account\UpdateProfileRequest;
use App\Http\Requests\Api\v1\Account\ChangePasswordRequest;

class AccountController extends Controller
{
    public function profile(Request $request)
    {
        $status =   'success';
        $user   =   $request->user()->load([
            'media',
            'deviceSettings' => function ($query) {
                $query->active();
            }
        ]);

        return Response::instance()
            ->withStatusCode('modules.' . $user->type, 'actions.read.' . $status)
            ->withStatus($status)
            ->withData((new MemberResource($user))->toArray($request))
            ->sendJson();
    }

    public function updateProfile(UpdateProfileRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   'Ok';

        try {

            $member_service->setModel($request->user())->setRequest($request)->store();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:profile')
            ->causedByAnonymous()
            ->performedOn(new User())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.update.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }

    public function deviceSettings(DeviceSettingRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   'Ok';
        $user       =   $request->user();

        try {

            $member_service->setModel($user)->setRequest($request)->storeDevice();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:device_settings')
            ->causedBy($user)
            ->performedOn(new DeviceSetting())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.device', 'actions.update.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }

    public function changePassword(ChangePasswordRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   'Ok';
        $user       =   $request->user();

        try {

            $member_service->setModel($user)->setRequest($request)->resetPassword();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:change_password')
            ->causedBy($user)
            ->performedOn($user)
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.member', 'actions.update.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
