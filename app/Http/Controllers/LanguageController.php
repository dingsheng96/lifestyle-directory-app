<?php

namespace App\Http\Controllers;

use App\Helpers\Message;
use App\Models\Language;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\LanguageDataTable;
use App\Http\Requests\LanguageRequest;
use App\Support\Services\LanguageService;
use App\DataTables\LanguageVersionDataTable;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:locale.read'])->only(['index', 'show']);
        $this->middleware(['can:locale.create'])->only(['create', 'store']);
        $this->middleware(['can:locale.update'])->only(['edit', 'update']);
        $this->middleware(['can:locale.delete'])->only(['delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LanguageDataTable $dataTable)
    {
        return $dataTable->render('locale.language.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $excel = asset('storage/mobile_labels.xlsx');

        return view('locale.language.create', compact('excel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request, LanguageService $language_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.language', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $language_service->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn(new Language())
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('locale.languages.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language, LanguageVersionDataTable $dataTable)
    {
        $language->load(['translations']);

        $excel = asset('storage/mobile_labels.xlsx');

        return $dataTable->with(['language' => $language])->render('locale.language.edit', compact('language', 'excel'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, Language $language, LanguageService $language_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.language', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $language->load(['translations']);

            $language_service->setModel($language)->setRequest($request)->store();

            $status     =   'success';
            $message    =   Message::instance()->format($action, $module, $status);

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        activity()->useLog('web')
            ->causedBy(Auth::user())
            ->performedOn($language)
            ->withProperties($request->all())
            ->log($message);

        return redirect()->route('locale.languages.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        //
    }
}
