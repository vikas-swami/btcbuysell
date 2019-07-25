<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\GeneralSettings;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use DB;
use App\PasswordReset;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/login';

    public function showResetForm(Request $request, $token)
    {
        $data['page_title'] = "Change Password";
         $tk =PasswordReset::where('token',$token)->first();
        
         if(is_null($tk))
         {
            $notification =  array('message' => 'Token Not Found!!','alert-type' => 'warning');
            return redirect()->route('user.password.request')->with($notification);
         }else{
                
            $email = $tk->email;

            return view('auth.passwords.reset',$data)->with(
                ['token' => $token, 'email' => $email]
            );
         }
    }


    public function reset(Request $request)
    {
        $this->validate($request,[
                'email' => 'required',
                'token' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
            ]);
            
        $reset = DB::table('password_resets')->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $user = User::where('email', $reset->email)->first();
        if ( $reset->status == 1)
        {
            return redirect()->route('login')->with('alert', 'Invalid Reset Link');
        }
        else
        {
            if($request->password == $request->password_confirmation)
            {
                $user->password = bcrypt($request->password);
                $user->save();

                DB::table('password_resets')->where('email', $user->email)->update(['status' => 1]);

                $msg =  'Password Changed Successfully';
                send_email($user->email,'Password Changed', $user->username, $msg);
                $sms =  'Password Changed Successfully';
                send_sms($user->mobile, $sms);

                return redirect()->route('login')->with('success', 'Password Changed');
            }
            else
            {
                return back()->with('alert', 'Password Not Matched');
            }
        }
        
    }
}
