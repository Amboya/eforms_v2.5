<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Main\ConfigWorkFlow;
use App\Models\PhrisUserDetailsModel;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }


    /**
     * Show the application's login form.
     *
     * @return View
     */
    public function showLoginForm()
    {


        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return RedirectResponse|\Illuminate\Http\Response|JsonResponse
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            //
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     * @throws ValidationException
     */
    protected function validateLogin(Request $request)
    {

//        $var = Hash::check($request->password, '$2y$10$MOK8auKH.6wPQl6KdyX0F.rVrSZooXWyzTF6yiRA3DoFWiPLPq5z.' );
//        dd($var);

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'staff_no';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //then get the user details in phris
        $phirs_user = PhrisUserDetailsModel::where('con_per_no', $request->staff_no)->first();
        //update user
        $user->name = $phirs_user->name ?? "";
        $user->email = $phirs_user->staff_email ?? $user->email;
        $user->phone = $phirs_user->mobile_no ?? $user->phone;
        $user->nrc = $phirs_user->nrc ?? $user->nrc;
        $user->contract_type = $phirs_user->contract_type ?? $user->contract_type;
        $user->con_st_code = $phirs_user->con_st_code ?? $user->con_st_code;
        $user->con_wef_date = $phirs_user->con_wef_date ?? $user->con_wef_date;
        $user->con_wet_date = $phirs_user->con_wet_date ?? $user->con_wet_date;
        $user->job_code = $phirs_user->job_code ?? $user->job_code;
        $user->station = $phirs_user->station ?? $user->station;
        $user->affiliated_union = $phirs_user->affiliated_union ?? $user->affiliated_union;

        //check if the job code changed
        if ($user->job_code != $phirs_user->job_code ?? "") {
            //trigger a check of user unit
            $user_unit = ConfigWorkFlow::where('user_unit_cc_code', $phirs_user->cc_code)
                ->where('user_unit_bc_code', $phirs_user->bu_code)->first();
            //trigger an update on work-flow
            if ($user_unit != null) {
                //update workflow
                $work_flow = ConfigWorkFlow::where($user->unit_column, $user->user_unit_code)
                    ->where($user->code_column, $user->job_code)
                    ->update([
                        $user->unit_column => $user_unit->user_unit_code,
                        $user->code_column => $phirs_user->job_code ?? $user->job_code
                    ]);
                //update user-unit details on user
                $user->job_code = $phirs_user->job_code ?? $user->job_code;
                $user->user_unit_code = $user_unit->user_unit_code;
                $user->user_unit_id = $user_unit->id;
            }

        }

        //count the users login times
        $user->total_login = 1 + ($user->total_login);
        $user->save();

        //check if phris has you activated or not  //
        if ($phirs_user->contract_type == config('constants.phris_user_not_active')) {
            Auth::logout();
            return back()->withErrors([
                'staff_no' => ['The provided credentials (Man Number) is no longer active in PHRIS.']
            ]);
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return Response
     *
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }


}
