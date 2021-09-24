<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Banner;
use App\Models\Category;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\BranchVisitorHistory;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MerchantResource;
use App\Http\Requests\Api\v1\HomeRequest;

class HomeController extends Controller
{
    public function index(HomeRequest $request)
    {
        $status     =   'success';
        $data       =   [];
        $latitude   =   $request->get('latitude', 0);
        $longitude  =   $request->get('longitude', 0);

        $banners    = Banner::with(['media'])->publish()->orderByDesc('created_at')->get();

        $categories = Category::with(['media'])->inRandomOrder()->limit(6)->get();

        $merchants  = User::with([
            'media', 'ratings', 'address' => function ($query) use ($latitude, $longitude) {
                $query->getDistanceByCoordinates($latitude, $longitude);
            }
        ])->validMerchant()->publish();

        // popular merchants
        $popular_merchants = (clone $merchants)->filterByLocationDistance($latitude, $longitude)->filterMerchantByRating()->orderBy('name')->limit(5)->get();

        // recent visit merchants
        $recent_visit_merchants = [];
        if ($user = $request->user('api')) {
            $recent_visit_merchants = (clone $merchants)->with(['visitorHistories'])
                ->whereHas('visitorHistories', function ($query) use ($user) {
                    $query->where('visitor_id', $user->id);
                })->orderBy(
                    BranchVisitorHistory::select('updated_at')
                        ->where('visitor_id', $user->id)
                        ->limit(1)
                        ->latest('updated_at')
                )->paginate(15, ['*'], 'page', $request->get('page'));
        }

        $data = [
            'banners' => BannerResource::collection($banners)->toArray($request),
            'categories' => CategoryResource::collection($categories)->toArray($request),
            'popular_merchants' =>  MerchantResource::collection($popular_merchants)->toArray($request),
            'recent_visit_merchants' =>  MerchantResource::collection($recent_visit_merchants)->toArray($request)
        ];

        return Response::instance()
            ->withStatusCode('modules.dashboard', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData($data)
            ->sendJson();
    }
}
