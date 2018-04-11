<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    /**
     * use username or email as the login name
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }


    /**
     * login by hand code
     *
     * @return mixed
     */
    public function showLoginForm()
    {

        Auth::logout();

        $user = User::find(1);

        $userdata = array(
            'name'     => $user->name,
            'password'  => 'secret'
        );

        if (Auth::attempt($userdata)) {

            // validation successful!
            // redirect them to the secure section or whatever
            // return Redirect::to('secure');
            // for now we'll just echo success (even though echoing in a controller is bad)
            echo 'SUCCESS!';

        } else {

            // validation not successful, send back to form
            return Redirect::to('login');

        }

    }

}
