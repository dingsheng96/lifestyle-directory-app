<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Career;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\CareerResource;
use App\Http\Requests\Api\v1\Career\CareerListRequest;
use App\Http\Requests\Api\v1\Career\CareerDetailsRequest;

class CareerController extends Controller
{
    public function index(CareerListRequest $request)
    {
        $status     =   'success';
        $merchant   =   User::with(['mainBranch.subBranches', 'subBranches'])->validMerchant()->where('id', $request->get('merchant_id'))->firstOrFail();

        $mainBranch     =   optional(optional($merchant->mainBranch)->pluck('id'))->toArray() ?? [];
        $subBranches    =   optional(optional($merchant->subBranches)->pluck('id'))->toArray() ?? [];

        $branches   =   array_merge($mainBranch, $subBranches);
        $branches   =   array_merge($branches, [$merchant->id]);

        $careers = Career::with(['branch.address'])->whereIn('branch_id', collect($branches)->unique()->values())
            ->publish()->orderByDesc('created_at')
            ->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.career', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(CareerResource::collection($careers)->toArray($request))
            ->sendJson();
    }

    public function show(CareerDetailsRequest $request)
    {
        $status     =   'success';
        $message    =   'Ok';

        $career = Career::with(['branch' => function ($query) {
            $query->with(['address', 'media']);
        }])->where('id', $request->get('career_id'))
            ->whereHas('branch', function ($query) {
                $query->validMerchant();
            })->publish()->firstOrFail();


        return Response::instance()
            ->withStatusCode('modules.career', 'actions.read.' . $status)
            ->withMessage($message)
            ->withStatus($status)
            ->withData((new CareerResource($career))->withDetails()->toArray($request))
            ->sendJson();
    }
}
