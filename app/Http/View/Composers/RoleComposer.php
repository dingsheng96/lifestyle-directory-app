<?php

namespace App\Http\View\Composers;

use App\Models\Role;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RoleComposer
{
    /**
     * Create a new categories composer.
     *
     * @param  RoleRespository $roles
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
        $roles = Role::orderBy('name')->get();

        if (!Auth::user()->hasRole(Role::ROLE_SUPER_ADMIN)) {
            $roles->filter(function ($value) {
                return $value->name == Role::ROLE_SUPER_ADMIN;
            });
        }

        $view->with('roles', $roles);
    }
}
