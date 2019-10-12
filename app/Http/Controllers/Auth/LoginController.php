<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Admin\User as Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $staff = Staff::where('email', $request->username)->orWhere('username', $request->username)->first();
        $user = User::where('email', $request->username)->orWhere('username', $request->username)->orWhereHas('school', function ($query) use ($request) {
            $query->where('school_email', $request->username);
        })->first();
        if ($staff) {
            if (Hash::check($request->password, $staff->password)) {
                return Auth::guard('admin')->login(
                    $staff, $request->filled('remember')
                );
            }
        } elseif ($user) {
            if (Hash::check($request->password, $user->password)) {
                return Auth::guard()->login(
                    $user, $request->filled('remember')
                );
            }
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin');
        }
        return Auth::guard();
    }
}
