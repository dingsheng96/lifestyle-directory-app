<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Helpers\Response;
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
}
