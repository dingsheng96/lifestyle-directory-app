<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Http\Resources\MerchantResource;
use App\Http\Requests\Api\v1\Merchant\RatingRequest;
use App\Http\Requests\Api\v1\Merchant\RatingListRequest;
use App\Http\Requests\Api\v1\Merchant\MerchantListRequest;
use App\Http\Requests\Api\v1\Merchant\MerchantDetailsRequest;

class MerchantController extends Controller
{
    public function index(MerchantListRequest $request)
    {
        $status     =   'success';
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);

        $merchants = User::with([
            'media', 'ratings',
            'address' => function ($query) use ($latitude, $longitude) {
                $query->getDistanceByCoordinates($latitude, $longitude);
            }
        ])->merchant()->active()->approvedApplication()->publish()
            ->filterByLocationDistance($latitude, $longitude)
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
        $message    =   '';
        $data       =   [];

        $merchant_id    = $request->get('merchant_id');
        $latitude       = $request->get('latitude', 0);
        $longitude      = $request->get('longitude', 0);

        try {

            $merchant = User::with([
                'media', 'ratings', 'branchDetail', 'raters', 'categories', 'operationHours',
                'address' => function ($query) use ($latitude, $longitude) {
                    $query->getDistanceByCoordinates($latitude, $longitude);
                }
            ])->withCount(['careers'])
                ->merchant()->active()->approvedApplication()->publish()
                ->where('id', $merchant_id)
                ->firstOrFail();

            $similar_merchants = User::with([
                'media', 'ratings',
                'address' => function ($query) use ($latitude, $longitude) {
                    $query->getDistanceByCoordinates($latitude, $longitude);
                }
            ])->merchant()->active()->approvedApplication()
                ->where('id', '!=', $merchant_id)
                ->filterByLocationDistance($latitude, $longitude)
                ->filterByCategories($merchant->categories->pluck('id')->toArray())
                ->inRandomOrder()
                ->limit(6)
                ->get();

            $data       =   (new MerchantResource($merchant))->listing(false)->similarMerchant($similar_merchants)->toArray($request);
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

    public function ratings(RatingListRequest $request)
    {
        $status         =   'success';
        $merchant_id    =   $request->get('merchant_id');

        $merchant = User::with(['ratings'])->merchant()
            ->where('id', $merchant_id)->publish()
            ->active()->approvedApplication()->first();

        return Response::instance()
            ->withStatusCode('modules.rating', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData((new RatingResource($merchant))->toArray($request))
            ->sendJson();
    }

    public function storeRatings(RatingRequest $request)
    {
        DB::beginTransaction();

        $status         =   'success';
        $message        =   'Ok';
        $merchant_id    =   $request->get('merchant_id');
        $scale          =   $request->get('scale');
        $review         =   $request->get('review');
        $merchant       =   new User();

        try {

            $merchant = User::where('id', $merchant_id)->merchant()
                ->active()->approvedApplication()->publish()->firstOrFail();

            $merchant->ratings()->attach([
                $request->user()->id => [
                    'scale' => $scale,
                    'review' => $review
                ]
            ]);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message    =   $ex->getMessage();
            $status     =   'fail';
        }

        activity()->useLog('api')
            ->causedBy($request->user())
            ->performedOn($merchant)
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.rating', 'actions.create.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
