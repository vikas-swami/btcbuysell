<?php

namespace App\Http\Controllers\Auth;

use App\Gateway;
use App\Http\Controllers\Controller;
use App\UserCryptoBalance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\User;
use App\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;


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
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $basic = GeneralSettings::first();

        if ($basic->registration != 1)
        {
            return back()->with('alert', 'Registration Disable Now');
        }

        $data['page_title'] = "Sign Up";
        return view('auth.register', $data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|min:5|unique:users|regex:/^\S*$/u',
            'password' => 'required|string|min:4|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $basic = GeneralSettings::first();

        if ($basic->email_verification == 1) {
            $email_verify = 0;
        } else {
            $email_verify = 1;
        }

        if ($basic->sms_verification == 1) {
            $phone_verify = 0;
        } else {
            $phone_verify = 1;
        }

        $verification_code  = rand (1000,9999);
        $sms_code  = rand (1000 , 9999);
        $email_time = Carbon::parse()->addMinutes(5);
        $phone_time = Carbon::parse()->addMinutes(5);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'email_verify' => $email_verify,
            'verification_code' => $verification_code,
            'sms_code' => $sms_code,
            'email_time' => $email_time,
            'phone_verify' => $phone_verify,
            'phone_time' => $phone_time,
            'tauth' => 0,
            'tfver' => 1,
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {

        $gateway = Gateway::all();
        foreach ($gateway as $data){
            UserCryptoBalance::create([
               'user_id' => $user->id,
               'gateway_id' => $data->id,
               'balance' => 0.0000,
            ]);
        }


        $basic = GeneralSettings::first();

        if ($basic->email_verify == 1) {
            $email_code = rand (1000 , 9999);
            $text = "Your Verification Code Is: <b>$email_code</b>";
            $this->sendMail($user->email, $user->name, 'Email verification', $text);
            $user->verification_code = $email_code;
            $user->email_time = Carbon::parse()->addMinutes(5);
            $user->save();
        }
        if ($basic->phone_verify == 1) {
            $sms_code = rand (1000 , 9999);
            $txt = "Your phone verification code is: $sms_code";
            $to = $user->phone;
            $this->sendSms($to, $txt);
            $user->sms_code = $sms_code;
            $user->phone_time = Carbon::parse()->addMinutes(5);
            $user->save();
        }
    }

    public function sendSms($to, $text)
    {
        $basic = GeneralSettings::first();
        $appi = $basic->smsapi;
        $text = urlencode($text);
        $appi = str_replace("{{number}}", $to, $appi);
        $appi = str_replace("{{message}}", $text, $appi);
        $result = file_get_contents($appi);
    }


}
