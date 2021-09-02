<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Helpers\Response;
use App\Models\Favourable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Services\MemberService;
use App\Http\Resources\MerchantResource;
use App\Http\Requests\Api\v1\Wishlist\WishlistRequest;
use App\Http\Requests\Api\v1\Wishlist\UpdateWishlistRequest;

class WishlistController extends Controller
{
    public function index(WishlistRequest $request)
    {
        $status     =   'success';
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);
        $user       =   $request->user();

        $merchants = User::with([
            'media', 'ratings',
            'address' => function ($query) use ($latitude, $longitude) {
                $query->getDistanceByCoordinates($latitude, $longitude);
            }
        ])->validMerchant()->whereHas('favouriteBy', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->orderBy('name')->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.wishlist', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(MerchantResource::collection($merchants)->toArray($request))
            ->sendJson();
    }

    public function update(UpdateWishlistRequest $request, MemberService $member_service)
    {
        DB::beginTransaction();

        $status     =   'success';
        $message    =   'Ok';
        $user       =   $request->user();

        try {

            $member_service->setModel($user)->setRequest($request)->storeWishlist();

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message    =   $ex->getMessage();
            $status     =   'fail';
        }

        activity()->useLog('api')
            ->causedBy($user)
            ->performedOn(new Favourable())
            ->withProperties($request->all())
            ->log($message);

        return Response::instance()
            ->withStatusCode('modules.wishlist', 'actions.update.' . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->sendJson();
    }
}
