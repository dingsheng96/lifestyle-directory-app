<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Response;
use App\Models\TacNumber;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\v1\VerifyTacRequest;

class TacController extends Controller
{
    public function verify(VerifyTacRequest $request)
    {
        DB::beginTransaction();

        $status     =   'success';
        $message    =   'Ok';

        try {

            $tac = TacNumber::where('mobile_no', $request->get('phone'))
                ->where('purpose', $request->get('purpose'))
                ->active()->unverified()->notExpired()
                ->latest('created_at')->firstOrFail();

            throw_if(!Hash::check($request->get('tac'), $tac->tac), new \Exception('The verification code is invalid.'));

            $tac->update([
                'status' => TacNumber::STATUS_INACTIVE,
                'verified_at' => now()
            ]);

            DB::commit();

            activity()->useLog('api:verify_tac')
                ->causedByAnonymous()
                ->performedOn($tac)
                ->withProperties($request->except(['tac']))
                ->log($message);
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message    =   'The verification code is invalid.';
            $status     =   'fail';
        }

        return Response::instance()
            ->withStatusCode('modules.tac', 'actions.authenticate.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
