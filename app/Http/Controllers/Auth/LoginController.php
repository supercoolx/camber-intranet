<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\ValidationException;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
    }

    // protected function authenticated(Request $request)
    //     {
    //         return redirect()->intended();
    //     }

    /**
     * Check either username or email.
     * @return string
     */
    public function username()
    {
        $identity = request()->get('email');
        $fieldName = filter_var($identity, FILTER_VALIDATE_EMAIL) || $identity != 'admin' ? 'email' : 'name';
        request()->merge([$fieldName => $identity]);
        return $fieldName;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */

    // protected function redirectTo()
    // {

    //     if (auth()->user()->is_admin) {
    //         // dd(auth()->user()->is_admin);
    //         return '/admin';
    //     } else if (auth()->check()) {
    //         return '/';
    //     } else {
    //         return '/login';
    //     }
    // }

}
