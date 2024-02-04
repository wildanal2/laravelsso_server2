<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    public $account = null;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Login Attempt Check
        $this->account = User::where('email', $request->input('email'))->first();
        if ($this->account && $this->account->failed_try > 10) {
            $this->account->is_active = 0;
            $this->account->save();
            $message = "Your Account is suspended, Please Contact Customer Support immediately.";
            Log::warning(json_encode([
                "message" => $message,
                "scope" => "Limit Login Attamp",
                "data" => $request->all(),
            ]));
            return $this->responseBack($message, 500);
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        if ($this->account) {
            $this->account->failed_try = $this->account->failed_try + 1;
            $this->account->save();
        }
        Log::warning(json_encode([
            "message" => "Username of Password not match",
            "scope" => "Failed Login Attamp",
            "data" => $request->all(),
        ]));

        return $this->sendFailedLoginResponse($request);
    }


    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        // Reset & clear LoginAttempts
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    protected function clearLoginAttempts(Request $request)
    {
        if ($this->account) {
            $this->account->failed_try = 0;
            $this->account->save();
        }
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function authenticated(Request $request, $user)
    {
        // Check Status User
        if ($user->is_active != 1) {
            Auth::logout();
            
            $message = "Your Account not Active, Please Contact Customer Support immediately.";
            Log::warning(json_encode([
                "message" => $message,
                "scope" => "Limit Login Attamp",
                "auth" => $user->toArray(),
            ]));

            return $this->responseRedirect('login', $message, 500); 
        } 

        // Login Success
        Log::info(json_encode([
            "message" => "Success Login",
            "scope" => "Login success",
            "auth" => $user->toArray(),
        ]));
    }

}
