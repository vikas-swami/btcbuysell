<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\User;



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
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    public function authenticated(Request $request, $user)
    {



        if($user->status == 0){
            $this->guard()->logout();
            $notification =  array('message' => 'Sorry Your Account is Block Now.!','alert-type' => 'warning');
            return redirect('/login')->with($notification);
        }

        $ip = NULL; $deep_detect = TRUE;

        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

    }



    public function logout(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        if(Auth::user()->tauth == 1)
        {
            $user['tfver'] = 0;
        }else{
            $user['tfver'] = 1;

        }
        $user->save();
        
        Auth::guard()->logout();
        session()->flash('message', 'Just Logged Out!');
        return redirect('/login');
    }
}
