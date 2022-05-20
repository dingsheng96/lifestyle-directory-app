<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Models\User;
use App\Helpers\Message;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Providers\RouteServiceProvider;
use App\Support\Services\MerchantService;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Merchant\Auth\AccountSetupRequest;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $merchant = User::with('branchDetail')
            ->merchant()
            ->where('email', $request->get('email'))
            ->first();

        return $merchant->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('merchant.auth.verify', compact('merchant'));
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();

        if (!hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect($this->redirectPath());
        }

        $request->request->add(['email' => $user->email]);

        return $this->show($request);
    }

    public function redirectPath()
    {
        return route('merchant.login');
    }

    public function accountSetup(AccountSetupRequest $request, User $user, MerchantService $merchant_service)
    {
        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $status     =   'fail';

        try {

            $status  = 'success';
            $message = Message::instance()->format($action, $module, $status);

            return DB::transaction(function () use ($merchant_service, $request, $message, $user) {

                if ($user->is_main_branch) {
                    $merchant = $merchant_service
                        ->setModel($user)
                        ->setRequest($request)
                        ->store()
                        ->setLocationCoordinates()
                        ->getModel();
                } elseif ($user->is_sub_branch) {
                    $merchant = $merchant_service
                        ->setModel($user)
                        ->setRequest($request)
                        ->storeBranch($user->mainBranch()->first())
                        ->getModel();
                }

                activity()->useLog('merchant:register')
                    ->causedByAnonymous()
                    ->performedOn($merchant)
                    ->withProperties($request->except(['password', 'password_confirmation']))
                    ->log($message);

                if ($merchant->markEmailAsVerified()) {
                    event(new Verified($merchant));
                }

                if ($response = $this->verified($request)) {
                    return $response;
                }

                return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect($this->redirectPath())->with('verified', true);
            });
        } catch (\Error | \Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = User::merchant()->where('id', $request->get('user'))->first();

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : back();
        }

        $user->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? new JsonResponse([], 202)
            : back()->with('resent', true);
    }
}
