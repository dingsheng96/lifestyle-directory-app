<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\CountryState;

class CountryStateComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  TopServiceRepository $top_service
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('countryStates', CountryState::orderBy('name')->get());
    }
}
