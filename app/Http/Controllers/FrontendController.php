<?php

namespace App\Http\Controllers;

use App\AdvertiseDeal;
use App\CryptoAddvertise;
use App\GeneralSettings;
use App\Slider;
use App\User;
use App\UserLogin;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Menu;
use App\Gateway;
use App\Crypto;
use App\Currency;
use App\Faq;
use App\Advertisment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FrontendController extends Controller{



 public function authCheck()
    {


        if(Auth::user()->status == '1' && Auth::user()->email_verify == 1 && Auth::user()->phone_verify == 1 && Auth::user()->tfver == 1)
        {
            return redirect('user/home');
        }
        else
        {
            return view('auth.noauthor');
        }
    }
    
    public function sendemailver()
    {
        $user = User::find(Auth::id());

        $chktm = Carbon::parse($user->email_time)->addMinutes(1);

        if ($chktm > Carbon::now())
        {
            $delay = Carbon::now()->diffInSeconds($chktm);
            return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        }
        else
        {
            $code = substr(rand(),0,6);
            $message = 'Your Verification code is: '.$code;
            $user['verification_code'] = $code ;
            $user['email_time'] = Carbon::now();
            $user->save();
            
            send_email($user->email, $user->name, 'Verification Code', $message);
        
            $sms = $message;
            send_sms($user['mobile'], $sms);
        
            return back()->with('success', 'Email verification code sent succesfully');
        }

    }
    public function sendsmsver()
    {
        $user = User::find(Auth::id());
       $chktm = Carbon::parse($user->email_time)->addMinutes(1);

        if ($chktm > Carbon::now())
        {
            $delay = Carbon::now()->diffInSeconds($chktm);
            return back()->with('alert', 'Please Try after '.$delay.' Seconds');
        }
        else
        {
            $code = substr(rand(),0,6);
            $sms =  'Your Verification code is: '.$code;
            $user['sms_code'] = $code;
            $user['phone_time'] = Carbon::now();
            $user->save();

           send_sms($user->mobile, $sms);
            return back()->with('success', 'SMS verification code sent succesfully');
        }


    }

    public function emailverify(Request $request)
    {

        $this->validate($request, [
            'code' => 'required'
        ]);
        
        $user = User::find(Auth::id());

        $code = $request->code;
        
        
        if ($user->verification_code == $code)
        {
            $user['email_verify'] = 1;
            $user['verification_code'] = substr(rand(),0,6);
            $user['email_time'] = Carbon::now();
            $user->save();

            return redirect('user/home')->with('success', 'Email Verified');
        }
        else
        {
            return back()->with('alert', 'Wrong Verification Code');
        }

    }

    public function smsverify(Request $request)
    {

        $this->validate($request, [
            'code' => 'required'
        ]);
        
        $user = User::find(Auth::id());

        $code = $request->code;
        if ($user->sms_code == $code)
        {
            $user['phone_verify'] = 1;
            $user['sms_code'] = substr(rand(),0,6);
            $user['phone_time'] = Carbon::now();
            $user->save();

            return redirect('user/home')->with('success', 'SMS Verified');
        }
        else
        {
            return back()->with('alert', 'Wrong Verification Code');
        }

    }

    public function verify2fa( Request $request)
    {
        $user = User::find(Auth::id());

        $this->validate($request, [
                'code' => 'required',
            ]);
            
        $google2fa = new Google2FA();
        $secret = $request->code;
        $valid = $google2fa->verifyKey($user->secretcode, $secret);
        
       
     
        if ($valid) {
            $user['tfver'] = 1;
            $user->save();
            return redirect('user/home');
        } else {

            return back()->with('alert', 'Wrong Verification Code');
        }

    }
    
    public function index()
    {
        $data['page_title'] = "home";
        $slider = Slider::find(5);
        $coin = Gateway::all();
        $methods = Crypto::where('status', 1)->get();
        $currency = Currency::where('status', 1)->get();

        $sell_btc = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 505)->take(5)->inRandomOrder()->get();
        $sell_eth = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 506)->take(5)->inRandomOrder()->get();
        // $sell_doge = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 509)->where('user_id', '!=', Auth::id())->take(5)->inRandomOrder()->get();
        // $sell_lite = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 510)->where('user_id', '!=', Auth::id())->take(5)->inRandomOrder()->get();

        $buy_btc = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 505)->take(5)->inRandomOrder()->get();
        $buy_eth = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 506)->take(5)->inRandomOrder()->get();
        // $buy_doge = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 509)->where('user_id', '!=', Auth::id())->take(5)->inRandomOrder()->get();
        // $buy_lite = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 510)->where('user_id', '!=', Auth::id())->take(5)->inRandomOrder()->get();

        return view('front.index', compact('slider', 'coin',
            'methods', 'currency', 'sell_btc', 'sell_eth', 
            'buy_btc', 'buy_eth'));
    }

    public function menu($slug)
    {
        $menu = $data['menu'] =  Menu::whereSlug($slug)->first();
        $data['page_title'] = $menu->name;
        return view('layouts.menu',$data);
    }

    public function contactUs()
    {
        $data['page_title'] = "Contact Us";
        return view('layouts.contact',compact('data'));
    }

    public function termsView()
    {
        $page_title = "Our Terms";
        return view('layouts.terms',compact('page_title'));
    }

    public function policyView()
    {
        $page_title = "Our Policy";
        return view('layouts.policy',compact('page_title'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'message' => 'required'
        ]);

        $general = GeneralSettings::first();

        $subject = "Contact Us";
        send_email($general->email,'I am'.$request->name, $subject,$request->message);
        $notification =  array('message' => 'Contact Message Send.', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function sell_btc()
    {
        $coin = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 505)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
        $type = 1;
        return view('front.sell_buy',compact('coin', 'type'));
    }
    public function sell_eth()
    {
        $coin = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 506)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
        $type = 1;
        return view('front.sell_buy',compact('coin', 'type'));
    }
    // public function sell_doge()
    // {
    //     $coin = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 509)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
    //     $type = 1;
    //     return view('front.sell_buy',compact('coin', 'type'));
    // }
    // public function sell_lite()
    // {
    //     $coin = CryptoAddvertise::where('add_type', 2)->where('gateway_id', 510)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
    //     $type = 1;
    //     return view('front.sell_buy',compact('coin', 'type'));
    // }
    
    public function buy_btc()
    {
       $coin = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 505)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
       $type = 2;
        return view('front.sell_buy',compact('coin', 'type'));
    }

    public function buy_eth()
    {
       $coin = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 506)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
       $type = 2;
        return view('front.sell_buy',compact('coin', 'type'));
    }

    // public function buy_doge()
    // {
    //    $coin = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 509)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
    //    $type = 2;
    //     return view('front.sell_buy',compact('coin', 'type'));
    // }

    // public function buy_lite()
    // {
    //    $coin = CryptoAddvertise::where('add_type', 1)->where('gateway_id', 510)->where('user_id', '!=', Auth::id())->latest()->paginate(20);
    //    $type = 2;
    //     return view('front.sell_buy',compact('coin', 'type'));
    // }

    public function viewSlug($id)
    {
        $coin = CryptoAddvertise::findOrFail($id);

        $price = $coin->price;

        return view('front.view',compact('coin', 'price'));
    }

    public function searchRe(Request $request)
    {
        $request->validate([
            'add_type' => 'required',
            'gateway_id' => 'required',
            'method_id' => 'required',
            'currency_id' => 'required',
        ]);

        $coin = CryptoAddvertise::where('add_type', $request->add_type)->where('gateway_id', $request->gateway_id)
            ->where('method_id', $request->method_id)
            ->where('currency_id', $request->currency_id)
            ->where('user_id', '!=', Auth::id())->latest()->paginate(20);

        $count = count($coin);

        $type = $request->add_type == 1? '2':'1';

        if ($count != 0){
            return view('front.sell_buy',compact('coin', 'type'));
        }else{
            return back()->with('alert', 'Not Found');
        }

    }

    public function profileView($username)
    {
        $user = User::where('username', $username)->first();

        if (empty($user)){
            return redirect('/');
        }

        $id = intval($user->id);

        $trade_btc = AdvertiseDeal::where('gateway_id', 505)->where('status', 1)->where(function($query) use ($id){
            $query->where('to_user_id', $id);
            $query->orWhere('from_user_id', $id);
        })->sum('coin_amount');

        $trade_etc = AdvertiseDeal::where('gateway_id', 506)->where('status', 1)->where(function($query) use ($id){
            $query->where('to_user_id', $id);
            $query->orWhere('from_user_id', $id);
        })->sum('coin_amount');

        $trade_doge = AdvertiseDeal::where('gateway_id', 509)->where('status', 1)->where(function($query) use ($id){
            $query->where('to_user_id', $id);
            $query->orWhere('from_user_id', $id);
        })->sum('coin_amount');

        $trade_lite = AdvertiseDeal::where('gateway_id', 510)->where('status', 1)->where(function($query) use ($id){
            $query->where('to_user_id', $id);
            $query->orWhere('from_user_id', $id);
        })->sum('coin_amount');

        $first_buy = AdvertiseDeal::where('from_user_id', $user->id)->where('status', 1)->first();

        $last_login = UserLogin::orderBy('id', 'desc')->where('user_id', $user->id)->first();

        $coin = CryptoAddvertise::where('user_id', $user->id)->paginate(5);

        return view('front.profile',compact('user', 'trade_btc',
            'trade_etc','trade_doge','trade_lite', 'first_buy','last_login', 'coin'));
    }


}
