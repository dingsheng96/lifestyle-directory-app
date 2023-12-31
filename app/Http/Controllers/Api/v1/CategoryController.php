<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Api\v1\CategoryRequest;

class CategoryController extends Controller
{
    public function index(CategoryRequest $request)
    {
        $status = 'success';

        $categories = Category::with(['media'])->orderBy('name')->paginate(15, ['*'], 'page', $request->get('page'));

        return Response::instance()
            ->withStatusCode('modules.category', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(CategoryResource::collection($categories)->toArray($request))
            ->sendJson();
    }

    public function popular(Request $request)
    {
        $status = 'success';

        $categories = Category::with([
            'media', 'merchants.ratings'
        ])->whereHas('merchants');

        $popular_category_id = (clone $categories)->get()->mapWithKeys(function ($value) {
            return [
                $value->id => $value->merchants->filter(function ($value) {
                    return $value->ratings->avg('pivot.scale') >= 3;
                })->count()
            ];
        })->sortDesc()->keys()->take(6);

        $categories = $categories->whereIn('id', $popular_category_id)->get();

        return Response::instance()
            ->withStatusCode('modules.category', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData(CategoryResource::collection($categories)->toArray($request))
            ->sendJson();
    }
}
