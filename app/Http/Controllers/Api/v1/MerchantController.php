<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantResource;
use App\Http\Requests\Api\v1\Merchant\MerchantListRequest;
use App\Http\Requests\Api\v1\Merchant\MerchantDetailsRequest;

class MerchantController extends Controller
{
    public function index(MerchantListRequest $request)
    {
        $status = 'success';

        $lat = $request->get('latitude');
        $lng = $request->get('longitude');

        $merchants = User::with([
            'media', 'ratings',
            'address' => function ($query) use ($lat, $lng) {
                $query->getDistanceByCoordinates($lat, $lng);
            }
        ])->merchant()->active()->approvedApplication()
            ->filterByLocationDistance($lat, $lng)
            ->orderBy('name')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(MerchantResource::collection($merchants)->toArray($request))
            ->sendJson();
    }

    public function show(MerchantDetailsRequest $request)
    {
        $status     =   'fail';
        $data       =   [];
        $lat        =   $request->get('latitude');
        $lng        =   $request->get('longitude');
        $message    =   '';

        try {

            $merchant = User::with([
                'media', 'ratings', 'branchDetail', 'ratedBy',
                'address' => function ($query) use ($lat, $lng) {
                    $query->getDistanceByCoordinates($lat, $lng);
                }
            ])->withCount(['careers'])
                ->merchant()->active()->approvedApplication()
                ->where('id', $request->get('merchant_id'))
                ->firstOrFail();

            $data       =   (new MerchantResource($merchant))->listing(false)->toArray($request);
            $status     =   'success';
            $message    =   'Ok';
        } catch (\Error | \Exception $ex) {
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.read.' . $status)
            ->withMessage($message)
            ->withStatus($status)
            ->withData($data)
            ->sendJson();
    }
}
