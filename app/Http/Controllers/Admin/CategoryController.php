<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message;
use App\Models\Category;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\CategoryDataTable;
use App\Http\Requests\CategoryRequest;
use App\Support\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:category.read'])->only(['index', 'show']);
        $this->middleware(['can:category.create'])->only(['create', 'store']);
        $this->middleware(['can:category.update'])->only(['edit', 'update']);
        $this->middleware(['can:category.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, CategoryService $category_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.category', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $category_service->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn(new Category())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('categories.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load(['media']);

        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $category->load(['media']);

        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category, CategoryService $category_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.category', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $category_service->setModel($category)->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($category)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('categories.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('modules.category', 1));
        $status     =   'success';
        $message    =   Message::instance()->format($action, $module, 'success');

        $category->delete();

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($category)
            ->log($message);

        return Response::instance()
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('categories.index')
            ])
            ->sendJson();
    }
}
