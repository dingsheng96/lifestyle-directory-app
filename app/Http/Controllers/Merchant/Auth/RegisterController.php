<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Models\User;
use App\Helpers\Message;
use App\Helpers\Response;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Support\Services\MerchantService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Merchant\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest:' . User::USER_TYPE_MERCHANT);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $referral = request()->get('referral');

        return view('merchant.auth.register', compact('referral'));
    }

    public function register(RegisterRequest $request, MerchantService $merchant_service)
    {
        DB::beginTransaction();

        $action     =   Permission::ACTION_CREATE;
        $module     =   strtolower(trans_choice('modules.merchant', 1));
        $status     =   'fail';

        try {

            $merchant_service->setRequest($request)->store()
                ->setApplicationStatus(User::APPLICATION_STATUS_PENDING)
                ->setReferral('referral_code');
            // ->setLocationCoordinates();

            $status  = 'success';

            DB::commit();
        } catch (\Error | \Exception $e) {

            DB::rollBack();
            Log::error($e);
        }

        $message = Message::instance()->format($action, $module, $status);

        activity()->useLog('merchant:register')
            ->causedByAnonymous()
            ->performedOn(new User())
            ->withProperties($request->except(['password', 'password_confirmation']))
            ->log($message);

        // return redirect()->route('merchant.branches.index')->with($status, $message);
        return 'Application submitted';
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(User::USER_TYPE_MERCHANT);
    }
}
