<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Helpers\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            ? Response::instance()
            ->withStatusCode('modules.system', 'actions.force.login')
            ->withMessage(__('messages.unauthenticated'))
            ->sendJson(401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function customRenderables()
    {
        $this->renderable(function (AccessDeniedHttpException $e, $request) {

            if ($request->expectsJson()) {
                return Response::instance()
                    ->withStatusCode('modules.scope', 'actions.authenticate.fail')
                    ->withStatus('fail')
                    ->withMessage($e->getMessage())
                    ->sendJson(403);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {

            if ($request->expectsJson()) {
                return Response::instance()
                    ->withStatusCode('modules.route', 'actions.read.fail')
                    ->withStatus('fail')
                    ->withMessage('Not Found')
                    ->sendJson(404);
            }
        });

        $this->renderable(function (Exception $e, $request) {

            if ($request->expectsJson()) {
                return Response::instance()
                    ->withStatus('fail')
                    ->withMessage($e->getMessage())
                    ->withData(['error' => $e])
                    ->sendJson(404);
            }
        });

        $this->renderable(function (TokenMismatchException $e, $request) {

            if ($request->expectsJson()) {
                return Response::instance()
                    ->withStatusCode('modules.route', 'actions.read.fail')
                    ->withStatus('fail')
                    ->withMessage($e->getMessage())
                    ->sendJson(404);
            };
        });

        $this->renderable(function (InvalidSignatureException $e, $request) {

            return redirect()->route('merchant.login')->with('info', __('messages.email_verification_link_expired'));
        });
    }
}
