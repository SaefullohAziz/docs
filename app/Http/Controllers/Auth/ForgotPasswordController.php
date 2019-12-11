<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\User;
use App\Admin\User as Admin;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('guest:admin');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        // $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->whereNull('deleted_at')->first();
        $admin = Admin::where('email', $request->email)->orWhere('username', $request->email)->whereNull('deleted_at')->first();

        if ($user) {
            $response = $this->broker()->sendResetLink(
                ['email' => $user->email]
            );
        }elseif ($admin) {
            $response = $this->brokerAdmin()->sendResetLink(
                ['email' => $admin->email]
            );
        }else{
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );
        }
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required']);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(['email', 'username']);
    }

    public function broker()
    {
        return Password::broker(); 
    }

    public function brokerAdmin()
    {
        return Password::broker('admins'); 
    }

    /**
     * Show the form for reset password
     *
     * @return void
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', ['title' => 'Reset Password']);
    }
}
