<?php

namespace App\Http\View\Composers;

use App\Helpers\Status;
use App\Models\Service;
use App\Models\Category;
use Illuminate\View\View;

class ActiveStatusComposer
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
        $view->with('statuses', (new Status())->activeStatus());
    }
}
