<?php

namespace App\Http\Controllers;

use App\Helpers\Misc;
use App\Models\Price;
use App\Helpers\Status;
use App\Models\Product;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\ProductAttribute;
use App\DataTables\PriceDataTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProductAttributeRequest;
use App\Support\Facades\ProductAttributeFacade;

class ProductAttributeController extends Controller
{
    public $stock_types, $statuses, $slot_types, $validity_types;

    public function __construct()
    {
        $this->stock_types      =   Misc::instance()->productStockTypes();
        $this->slot_types       =   Misc::instance()->adsSlotType();
        $this->validity_types   =   Misc::instance()->validityType();
        $this->statuses         =   (new Status())->activeStatus();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        return view('product.attribute.create', [
            'product' => $product,
            'stock_types' => $this->stock_types,
            'statuses' => $this->statuses,
            'slot_types' => $this->slot_types,
            'validity_types' => $this->validity_types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductAttributeRequest $request, Product $product)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute = ProductAttributeFacade::setParent($product)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('products.edit', ['product' => $product->id])
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn(new ProductAttribute())
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, ProductAttribute $attribute)
    {
        $attribute->load([
            'prices' => function ($query) {
                $query->with(['currency.countries'])->defaultPrice();
            }
        ]);

        $default_price = $attribute->prices->first();

        return view('product.attribute.show', compact('product', 'attribute', 'default_price'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, ProductAttribute $attribute)
    {
        $attribute->load([
            'prices' => function ($query) {
                $query->defaultPrice();
            }
        ]);

        $default_price = $attribute->prices->first();

        return view('product.attribute.edit', [
            'product' => $product,
            'attribute' => $attribute,
            'statuses' => $this->statuses,
            'stock_types' => $this->stock_types,
            'default_price' => $default_price,
            'slot_types' => $this->slot_types,
            'validity_types' => $this->validity_types
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductAttributeRequest $request, Product $product, ProductAttribute $attribute)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $product->load(['productAttributes.prices']);

            $attribute = ProductAttributeFacade::setParent($product)
                ->setModel($attribute)
                ->setRequest($request)
                ->storeData()
                ->getModel();

            DB::commit();

            $status  =  'success';
            $message =  Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($message);

            return redirect()->route('products.edit', ['product' => $product->id])
                ->withSuccess($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->withProperties($request->all())
                ->log($e->getMessage());

            return redirect()->back()->with('fail', $message)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductAttribute $attribute)
    {
        $action     =   Permission::ACTION_DELETE;
        $module     =   strtolower(trans_choice('labels.attribute', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $attribute->prices()->delete();
            $attribute->delete();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($message);
        } catch (\Error | \Exception $e) {

            DB::rollBack();

            activity()->useLog('web')
                ->causedBy(Auth::user())
                ->performedOn($attribute)
                ->log($e->getMessage());
        }

        return Response::instance()
            ->withStatusCode('modules.product', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData([
                'redirect_to' => route('products.edit', ['product' => $product->id])
            ])
            ->sendJson();
    }
}
