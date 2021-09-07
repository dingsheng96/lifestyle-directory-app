<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Models\DeviceSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Services\DeviceService;
use App\Http\Requests\Api\v1\Account\DeviceSettingRequest;

class DeviceController extends Controller
{
    public function setup(DeviceSettingRequest $request, DeviceService $device_service)
    {
        DB::beginTransaction();

        $status     =   'fail';
        $message    =   'Ok';

        try {

            $device = DeviceSetting::firstOrNew(['device_id' => $request->get('device_id')]);

            $device_service->setModel($device)->setRequest($request)->store();

            $status = 'success';

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
        }

        activity()->useLog('api:device_settings')
            ->causedByAnonymous()
            ->performedOn(new DeviceSetting())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.device', 'actions.update.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
