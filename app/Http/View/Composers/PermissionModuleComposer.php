<?php

namespace App\Http\View\Composers;

use App\Models\Module;
use Illuminate\View\View;
use App\Models\Permission;

class PermissionModuleComposer
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
        $actions = Permission::select('action')
            ->groupBy('action')
            ->orderBy('action')->get();

        $modules = Module::with([
            'permissions' => function ($query) {
                $query->orderBy('action');
            }
        ])->orderBy('name')->get();

        $view->with('actions', $actions);
        $view->with('modules', $modules);
    }
}
