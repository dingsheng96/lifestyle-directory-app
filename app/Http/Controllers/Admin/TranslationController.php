<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message;
use App\Models\Language;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Support\Services\LanguageService;
use App\Exports\Locale\LanguageTranslationsExport;

class TranslationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:locale.create'])->only(['import', 'export']);
        $this->middleware(['can:locale.update'])->only(['import', 'export']);
    }

    public function import(Language $language, Request $request, LanguageService $language_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.language', 1));
        $message    =   Message::instance()->format($action, $module);
        $status     =   'fail';

        try {

            $language->load(['translations']);

            $language_service->setModel($language)->setRequest($request)->storeTranslations();

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

        return redirect()->route('admin.locales.languages.index')->with($status, $message);
    }

    public function export(Language $language)
    {
        $filename = $language->code . '_' . request()->get('version') . '.xlsx';

        return Excel::download(new LanguageTranslationsExport($language, request()->get('version')), $filename);
    }
}
