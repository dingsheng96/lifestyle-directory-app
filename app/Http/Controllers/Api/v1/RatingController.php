<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Rateable;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Support\Services\MemberService;
use App\Http\Requests\Api\v1\Rating\RatingRequest;
use App\Http\Requests\Api\v1\Rating\RatingListRequest;

class RatingController extends Controller
{
    public function index(RatingListRequest $request)
    {
        $status     =   'success';

        $merchant   =   User::with(['ratings'])->validMerchant()->where('id', $request->get('merchant_id'))->first();

        return Response::instance()
            ->withStatusCode('modules.rating', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData((new RatingResource($merchant))->toArray($request))
            ->sendJson();
    }

    public function store(RatingRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status         =   'success';
        $message        =   'Ok';
        $user           =   $request->user();

        try {

            $member_service->setModel($user)->setRequest($request)->storeMerchantRating();

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message    =   $ex->getMessage();
            $status     =   'fail';
        }

        activity()->useLog('api')
            ->causedBy($request->user())
            ->performedOn(new Rateable())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.rating', 'actions.create.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}