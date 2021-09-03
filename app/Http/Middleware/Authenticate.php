<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Helpers\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request, $guards)
        );
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request, array $guards = [])
    {
        if (!$request->expectsJson()) {

            $route_name = "login";

            if (is_array($guards) && count($guards) > 0) {

                $route_name = (in_array(User::USER_TYPE_ADMIN, $guards)) ? "admin.login" : "merchant.login";
            }

            return redirect()->route($route_name, $request->route()->parameters());
        }

        return Response::instance()
            ->withStatusCode('modules.system', 'actions.force.login')
            ->withMessage(__('messages.unauthenticated'))
            ->sendJson(401);
    }
}
