<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Language;
use App\Helpers\Response;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\LanguageTranslationResource;
use App\Http\Requests\Api\v1\LanguageTranslationRequest;

class LanguageController extends Controller
{
    public function languages(Request $request)
    {
        $status = 'success';

        $languages = Language::with(['translations'])->orderBy('name')->get();

        $data = $languages->mapWithKeys(function ($item) use ($request) {

            return [$item['code'] => (new LanguageResource($item))->toArray($request)];
        })->merge([
            'supported_locale' => $languages->pluck('code')->toArray()
        ])->toArray();

        return Response::instance()
            ->withStatusCode('modules.language', 'actions.index.' . $status)
            ->withStatus($status)
            ->withData($data)
            ->sendJson();
    }

    public function translations(LanguageTranslationRequest $request)
    {
        $status = 'success';

        $language   =   Language::with(['translations'])->where('code', $request->get('code'))->first();

        $translations  =   $language->translations->where('version', $language->current_version);

        return Response::instance()
            ->withStatusCode('modules.language', 'actions.show.' . $status)
            ->withStatus($status)
            ->withData((new LanguageTranslationResource($translations))->toArray($request))
            ->sendJson();
    }
}
