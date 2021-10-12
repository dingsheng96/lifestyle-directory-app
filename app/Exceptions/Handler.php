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

        $this->renderable(function (Exception $e, $request) {

            if ($request->expectsJson()) {

                $status = 'fail';
                $status_code = 404;
                $message = $e->getMessage();
                $status_code_prefix = 'modules.route';
                $status_code_suffix = 'actions.read.fail';

                if ($e instanceof AuthenticationException) {

                    $status_code = 401;
                    $status_code_prefix = 'modules.system';
                    $status_code_suffix = 'actions.force.login';
                    $message = __('messages.unauthenticated');
                } elseif ($e instanceof AccessDeniedHttpException) {

                    $status_code = 403;
                    $status_code_prefix = 'modules.scope';
                    $status_code_suffix = 'actions.authenticate.fail';
                } elseif ($e instanceof NotFoundHttpException) {

                    $message = __('messages.not_found');
                } elseif ($e instanceof InvalidSignatureException) {

                    $message = __('messages.email_verification_link_expired');
                }

                return Response::instance()
                    ->withStatusCode($status_code_prefix, $status_code_suffix)
                    ->withStatus($status)
                    ->withMessage($message)
                    ->sendJson($status_code);
            }
        });
    }
}
