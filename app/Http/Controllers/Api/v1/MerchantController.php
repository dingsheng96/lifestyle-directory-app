<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RatingResource;
use App\Http\Resources\MerchantResource;
use App\Support\Services\MerchantService;
use App\Http\Requests\Api\v1\Merchant\ReviewListRequest;
use App\Http\Requests\Api\v1\Merchant\MerchantListRequest;
use App\Http\Requests\Api\v1\Merchant\SearchMerchantRequest;
use App\Http\Requests\Api\v1\Merchant\MerchantDetailsRequest;
use App\Http\Requests\Api\v1\Merchant\PopularMerchantRequest;

class MerchantController extends Controller
{
    public function index(MerchantListRequest $request)
    {
        $status     =   'success';
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);

        $merchants = User::query()
            ->with([
                'media', 'ratings', 'favouriteBy', 'categories',
                'address' => function ($query) use ($latitude, $longitude) {
                    $query->getDistanceByCoordinates($latitude, $longitude);
                }
            ])
            ->validMerchant()
            ->publish()
            ->filterByLocationDistance($latitude, $longitude)
            ->whereHas('categories', function ($query) use ($request) {
                $query->whereIn('id', [$request->get('category_id')]);
            })
            ->orderBy('name')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(MerchantResource::collection($merchants)->toArray($request))
            ->sendJson();
    }

    public function show(MerchantDetailsRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $status     =   'success';
        $message    =   'Ok';
        $data       =   [];

        $merchant_id    = $request->get('merchant_id');
        $latitude       = $request->get('latitude', 0);
        $longitude      = $request->get('longitude', 0);

        try {

            $merchant = User::query()
                ->with([
                    'ratings', 'branchDetail', 'raters', 'categories',
                    'operationHours', 'favouriteBy',  'userSocialMedia',
                    'media' => function ($query) {
                        $query->orderBy('position');
                    },
                    'address' => function ($query) use ($latitude, $longitude) {
                        $query->getDistanceByCoordinates($latitude, $longitude);
                    }
                ])
                ->withCount(['careers', 'ratings'])
                ->validMerchant()->publish()
                ->where('id', $merchant_id)
                ->firstOrFail();

            $merchant_service->setModel($merchant)->setRequest($request)->storeVisitorHistory();

            $similar_merchants = User::query()
                ->with([
                    'ratings',
                    'media' => function ($query) {
                        $query->orderBy('position');
                    },
                    'address' => function ($query) use ($latitude, $longitude) {
                        $query->getDistanceByCoordinates($latitude, $longitude);
                    }
                ])
                ->validMerchant()
                ->where('id', '!=', $merchant_id)
                ->filterByLocationDistance($latitude, $longitude)
                ->filterByCategories($merchant->categories->pluck('id')->toArray())
                ->inRandomOrder()
                ->limit(6)
                ->get();

            $data = (new MerchantResource($merchant))
                ->listing(false)
                ->similarMerchant($similar_merchants)
                ->toArray($request);

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $status  = 'fail';
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.read.' . $status)
            ->withMessage($message)
            ->withStatus($status)
            ->withData($data)
            ->sendJson();
    }

    public function reviews(ReviewListRequest $request)
    {
        $status     =   'success';

        $merchant   =   User::with(['ratings', 'address'])
            ->publish()
            ->validMerchant()
            ->where('id', $request->get('merchant_id'))
            ->first()
            ->ratings()
            ->orderByDesc('pivot_created_at')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.rating', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(RatingResource::collection($merchant)->toArray($request))
            ->sendJson();
    }

    public function search(SearchMerchantRequest $request)
    {
        $status     =   'success';
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);

        $merchants = User::query()
            ->with([
                'media', 'ratings', 'favouriteBy', 'categories',
                'address' => function ($query) use ($latitude, $longitude) {
                    $query->getDistanceByCoordinates($latitude, $longitude);
                }
            ])
            ->validMerchant()
            ->publish()
            ->searchByInput($request->get('keyword'))
            ->filterByLocationDistance($latitude, $longitude)
            ->orderBy('name')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(MerchantResource::collection($merchants)->toArray($request))
            ->sendJson();
    }

    public function popular(PopularMerchantRequest $request)
    {
        $status     =   'success';
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);

        $merchants = User::query()
            ->with([
                'media', 'ratings', 'favouriteBy', 'categories',
                'address' => function ($query) use ($latitude, $longitude) {
                    $query->getDistanceByCoordinates($latitude, $longitude);
                }
            ])
            ->validMerchant()
            ->publish()
            ->filterMerchantByRating()
            ->filterByLocationDistance($latitude, $longitude)->orderBy('name')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(MerchantResource::collection($merchants)->toArray($request))
            ->sendJson();
    }
}
