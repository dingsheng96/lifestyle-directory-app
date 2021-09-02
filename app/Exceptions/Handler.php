<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->customRenderables();
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(
                Response::instance()
                    ->withStatusCode('modules.member', 'actions.authenticate.fail')
                    ->withStatus('fail')
                    ->withMessage($exception->getMessage())
                    ->getResponse(),
                401
            )
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function customRenderables()
    {
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            return $request->expectsJson()
                ? response()->json(
                    Response::instance()
                        ->withStatusCode('modules.scope', 'actions.authenticate.fail')
                        ->withStatus('fail')
                        ->withMessage($e->getMessage())
                        ->getResponse(),
                    403
                )
                : redirect()->route('login')->with('info', $e->getMessage());
        });

        $this->renderable(function (TokenMismatchException $e, $request) {

            return redirect()->route('login')->with('info', __('messages.session_expired'));
        });

        $this->renderable(function (InvalidSignatureException $e, $request) {

            return redirect()->route('login')->with('info', __('messages.email_verification_link_expired'));
        });
    }
}
