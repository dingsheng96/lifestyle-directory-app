<?php

namespace App\Http\View\Composers;

use App\Models\User;
use Illuminate\View\View;

class PendingApplicationComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  PendingApplicationRespository $verifications
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
        $view->with('pending_applications_count', User::pendingApplication()->count());
    }
}
