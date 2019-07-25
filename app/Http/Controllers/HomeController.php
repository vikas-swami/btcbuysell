<?php

namespace App\Http\Controllers;

use App\AdvertiseDeal;
use App\Crypto;
use App\CryptoAddvertise;
use App\Currency;
use App\DealConvertion;
use App\Deposit;
use App\Gateway;
use App\GeneralSettings;
use App\Lib\GoogleAuthenticator;
use App\Trx;
use App\User;
use App\UserCryptoBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use PragmaRX\Google2FA\Google2FA;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','CheckStatus']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $balance = UserCryptoBalance::whereUser_id(Auth::id())->get();
        return view('home', compact('balance'));
    }

    public function editProfile()
    {
        $page_title = 'Profile';
        $user = User::findOrFail(Auth::id());
        return view('user_panel.profile.profile', compact('page_title', 'user'));
    }

    public function submitProfile(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
            'phone' => 'required|max:255',
            'zip_code' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
        ]);
        $input = Input ::except(['_token', '_method']);
        User::whereId(Auth::id())->update($input);
        return back()->with('success', 'Profile Update Successful');
    }

    public function changePassword()
    {
        $page_title = 'Change Password';
        return view('user_panel.profile.change_password', compact('page_title'));
    }

    public function submitPassword(Request $request)
    {
        $this->validate($request,[
            'passwordold' =>'required',
            'password' => 'required|min:5|confirmed'
        ]);

        try {
            $c_password = User::find(Auth::id())->password;
            $c_id = User::find(Auth::id())->id;
            $user = User::findOrFail($c_id);

            if(Hash::check($request->passwordold, $c_password)){

                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();

                return redirect()->back()->with('success','Password Change Successfully.');
            }else{
                return redirect()->back()->withErrors('Password Not Match');
            }
        }catch (\PDOException $e) {
            return redirect()->back()->withErrors('Some Problem Occurs, Please Try Again!');
        }
    }


    public function deposit()
    {
        $data['gates'] = Gateway::whereStatus(1)->get();

        $data['user_address'] = UserCryptoBalance::where('user_id', Auth::user()->id)->get();
        return view('user_panel.deposit', $data);
    }

    public function sell_coin()
    {
        $coin = Gateway::all();
        $methods = Crypto::where('status', 1)->get();
        $currency = Currency::where('status', 1)->get();


        $all = file_get_contents("https://api.coinmarketcap.com/v2/ticker/");
        $ticker = json_decode($all, true);

        $btc_usd = $ticker['data'][1]['quotes']['USD']['price'];
        $lite_usd = $ticker['data'][2]['quotes']['USD']['price'];
        $doge_usd = $ticker['data'][74]['quotes']['USD']['price'];
        $eth_usd = $ticker['data'][1027]['quotes']['USD']['price'];

        return view('user_panel.sell_coin', compact('coin', 'methods',
            'currency', 'btc_usd', 'lite_usd', 'doge_usd', 'eth_usd'));

    }

    public function sell_buy_history()
    {
        $addvertise = CryptoAddvertise::whereUser_id(Auth::id())
            ->latest()->paginate(5);
        return view('user_panel.sell_buy_history', compact('coin', 'crypto', 'addvertise'));

    }

    public function currenyGet(Request $request)
    {
        $data = Currency::findOrFail($request->crypto);
        return response()->json($data) ;
    }

    public function addStore(Request $request)
    {
        $this->validate($request, [
            'gateway_id' => 'required',
            'crypto_id' => 'required',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|min:1',
            'term_detail' => 'required',
            'margin' => 'required|numeric',
            'payment_detail' => 'required',
            'currency' => 'required',
            'agree' => 'required',
        ]);

        if ($request->add_type == 1){
            $type = 'sell';
        }else{
            $type = 'buy';
        }

        $all = file_get_contents("https://api.coinmarketcap.com/v2/ticker/");
        $ticker = json_decode($all, true);

        $btc_usd = $ticker['data'][1]['quotes']['USD']['price'];
        $lite_usd = $ticker['data'][2]['quotes']['USD']['price'];
        $doge_usd = $ticker['data'][74]['quotes']['USD']['price'];
        $eth_usd = $ticker['data'][1027]['quotes']['USD']['price'];

        if($request->gateway_id == 505){
            $price = $btc_usd ;
        }elseif($request->gateway_id == 506){
            $price = $eth_usd;
        }elseif($request->gateway_id == 509){
            $price = $doge_usd;
        }else{
            $price =$lite_usd;
        }

        if ($request->agree == 1 && $type == "sell") {

            $cur = Currency::find($request->currency);

            $method = Crypto::find($request->crypto_id);

            if ($request->margin == 0){
                $after_margin = ($cur->usd_rate * $price * 1)/100;
            }else{
                $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            }

                $total_price = ($cur->usd_rate * $price)+$after_margin;

                CryptoAddvertise::create([
                    'user_id' => Auth::id(),
                    'add_type' => 1,
                    'gateway_id' => $request->gateway_id,
                    'method_id' => $request->crypto_id,
                    'currency_id' => $request->currency,
                    'margin' => $request->margin,
                    'price' => round($total_price,2),
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'term_detail' => $request->term_detail,
                    'payment_detail' => $request->payment_detail,
                    'status' => 0,
                ]);

                $message = "You Trade Advertise create complete. You want to".$request->add_type.' '.$request->min_amount.'-'.$request->max_amount.' '.$cur->name." . You choose ".$method->name." for transaction. Wait and your advertise on live now.";
                send_email(Auth::user()->email, Auth::user()->name, 'Advertise Create Successful', $message);
                send_sms(Auth::user()->phone, $message);

                return redirect()->back()->with('message','Advertise For Selling Create Successful.');



        }elseif ($request->agree == 1 && $type == "buy"){

            $method = Crypto::find($request->crypto_id);
            $cur = Currency::find($request->currency);

            if ($request->margin == 0){
                $after_margin = ($cur->usd_rate * $price * 1)/100;
            }else{
                $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            }

            $total_price = ($cur->usd_rate * $price)+$after_margin;


            CryptoAddvertise::create([
                'user_id' => Auth::id(),
                'add_type' => 2,
                'gateway_id' => $request->gateway_id,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency,
                'margin' => $request->margin,
                'price' => round($total_price,2),
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'status' => 0,
            ]);

            $message = "You Trade Advertise create complete. You want to".$request->add_type.' '.$request->min_amount.'-'.$request->max_amount.' '.$cur->name." . You choose ".$method->name." for transaction. Wait and your advertise on live now.";

            send_email(Auth::user()->email, Auth::user()->name, 'Advertise Create Successful', $message);
            send_sms(Auth::user()->phone, $message);
            return redirect()->back()->with('message','Advertise For Buying Create Successful.');

        }else{
            return redirect()->back()->withErrors('Please Read Our Terms And Condition');
        }


    }

    public function addUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'add_type' => 'required',
            'gateway_id' => 'required',
            'crypto_id' => 'required',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|min:1',
            'term_detail' => 'required',
            'margin' => 'required|numeric',
            'payment_detail' => 'required',
            'currency' => 'required',
        ]);



        $all = file_get_contents("https://api.coinmarketcap.com/v2/ticker/");
        $ticker = json_decode($all, true);

        $btc_usd = $ticker['data'][1]['quotes']['USD']['price'];
        $lite_usd = $ticker['data'][2]['quotes']['USD']['price'];
        $doge_usd = $ticker['data'][74]['quotes']['USD']['price'];
        $eth_usd = $ticker['data'][1027]['quotes']['USD']['price'];

        if($request->gateway_id == 505){
            $price = $btc_usd ;
        }elseif($request->gateway_id == 506){
            $price = $eth_usd;
        }elseif($request->gateway_id == 509){
            $price = $doge_usd;
        }else{
            $price =$lite_usd;
        }

        if ($request->add_type == "sell")
        {
            $cur = Currency::find($request->currency);

                $method = Crypto::find($request->crypto_id);
                if ($request->margin == 0){
                    $after_margin = ($cur->usd_rate * $price * 1)/100;
                }else{
                    $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
                }

                $total_price = ($cur->usd_rate * $price)+$after_margin;

                CryptoAddvertise::whereId($id)->update([
                    'user_id' => Auth::id(),
                    'add_type' => 1,
                    'gateway_id' => $request->gateway_id,
                    'method_id' => $request->crypto_id,
                    'currency_id' => $request->currency,
                    'margin' => $request->margin,
                    'price' => round($total_price,2),
                    'min_amount' => $request->min_amount,
                    'max_amount' => $request->max_amount,
                    'term_detail' => $request->term_detail,
                    'payment_detail' => $request->payment_detail,
                    'status' => 0,
                ]);

                $message = "You Trade Advertise create complete. You want to".$request->add_type.' '.$request->min_amount.'-'.$request->max_amount.' '.$cur->name." . You choose ".$method->name." for transaction. Wait and your advertise on live now.";
                send_email(Auth::user()->email, Auth::user()->name, 'Advertise Create Successful', $message);
                send_sms(Auth::user()->phone, $message);

                return redirect('user/advertise/history')->with('message','Advertise For Selling Update Successful.');



        }elseif ($request->add_type == "buy"){

            $method = Crypto::find($request->crypto_id);
            $cur = Currency::find($request->currency);

            if ($request->margin == 0){
                $after_margin = ($cur->usd_rate * $price * 1)/100;
            }else{
                $after_margin = ($cur->usd_rate * $price * $request->margin)/100;
            }

            $total_price = ($cur->usd_rate * $price)+$after_margin;


            CryptoAddvertise::whereId($id)->update([
                'user_id' => Auth::id(),
                'add_type' => 2,
                'gateway_id' => $request->gateway_id,
                'method_id' => $request->crypto_id,
                'currency_id' => $request->currency,
                'margin' => $request->margin,
                'price' => round($total_price,2),
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
                'term_detail' => $request->term_detail,
                'payment_detail' => $request->payment_detail,
                'status' => 0,
            ]);

            $message = "You Trade Advertise update complete. You want to".$request->add_type.' '.$request->min_amount.'-'.$request->max_amount.' '.$cur->name." . You choose ".$method->name." for transaction. Wait and your advertise on live now.";

            send_email(Auth::user()->email, Auth::user()->name, 'Advertise Update Successful', $message);
            send_sms(Auth::user()->phone, $message);
            return redirect('user/advertise/history')->with('message','Advertise For Buying Update Successful.');

        }else{
            return redirect()->back()->withErrors('Please Read Our Terms And Condition');
        }


    }

    public function addEdit($id)
    {
        $add = CryptoAddvertise::where('user_id', Auth::id())->whereId($id)->first();

        if (isset($add)){
            $coin = Gateway::all();
            $methods = Crypto::where('status', 1)->get();
            $currency = Currency::where('status', 1)->get();


            $all = file_get_contents("https://api.coinmarketcap.com/v2/ticker/");
            $ticker = json_decode($all, true);

            $btc_usd = $ticker['data'][1]['quotes']['USD']['price'];
            $lite_usd = $ticker['data'][2]['quotes']['USD']['price'];
            $doge_usd = $ticker['data'][74]['quotes']['USD']['price'];
            $eth_usd = $ticker['data'][1027]['quotes']['USD']['price'];
            return view('user_panel.sell_buy_edit', compact('add','coin', 'methods',
                'currency', 'btc_usd', 'lite_usd', 'doge_usd', 'eth_usd'));
        }else{
            return redirect()->back();
        }

    }

    public function storeDealBuy(Request $request, $id)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
        ]);



        $add = CryptoAddvertise::findOrFail($id);

        $bal =  UserCryptoBalance::where('user_id', Auth::id())->where('gateway_id', $add->gateway_id)->first();

        $trans_id = rand(100000, 999999);

        $usd_rate = $request->amount / $add->currency->usd_rate ;

        $coin_amount = $usd_rate/$add->price;

        if ($add->add_type == 2 && $bal->balance <= $coin_amount)
        {
            return redirect()->back()->withErrors('You Have Not Enough Balance To Sell.');
        }

        if ($add->add_type == 1){

            $to_user =UserCryptoBalance::where('user_id', $add->user_id)->where('gateway_id', $add->gateway_id)->first();
            $after_bal = $to_user->balance - $coin_amount;
            $to_user->balance = $after_bal;
            $to_user->save();

            $deal = AdvertiseDeal::create([
                'gateway_id' => $add->gateway_id,
                'method_id' => $add->method_id,
                'currency_id' => $add->currency_id,
                'term_detail' => $add->term_detail,
                'payment_detail' => $add->payment_detail,
                'price' => $add->price,
                'add_type' =>  '1',
                'to_user_id' => $add->user_id,
                'from_user_id' => Auth::id(),
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $request->amount,
                'status' => 0,
            ]);

            if ($request->detail != null)
            {
                DealConvertion::create([
                    'deal_id' => $deal->id,
                    'type' => 1,
                    'deal_detail' => $request->detail,
                    'image' => null,
                ]);
            }

            $to_user = User::findOrFail($add->user_id);
        }else{

            $to_user =UserCryptoBalance::where('user_id', Auth::id())->where('gateway_id', $add->gateway_id)->first();
            $after_bal = $to_user->balance - $coin_amount;
            $to_user->balance = $after_bal;
            $to_user->save();

            $deal = AdvertiseDeal::create([
                'gateway_id' => $add->gateway_id,
                'method_id' => $add->method_id,
                'currency_id' => $add->currency_id,
                'term_detail' => $add->term_detail,
                'payment_detail' => $add->payment_detail,
                'price' => $add->price,
                'add_type' => '2',
                'to_user_id' => $add->user_id,
                'from_user_id' => Auth::id(),
                'trans_id' => $trans_id,
                'usd_amount' => $usd_rate,
                'coin_amount' => $coin_amount,
                'amount_to' => $request->amount,
                'status' => 0,
            ]);

            if ($request->detail != null)
            {
                DealConvertion::create([
                    'deal_id' => $deal->id,
                    'type' => 1,
                    'deal_detail' => $request->detail,
                    'image' => null,
                ]);
            }

            $to_user = User::findOrFail(Auth::id());
        }

        $message = "There's a ".$deal->add_type == 1? 'buying':'selling'." request for you. ".Auth::user()->name." wants 
        to make deal with you. ".Auth::user()->name.' bit '.$request->amount.$deal->currency->name.'';

        send_email($to_user->email, $to_user->name, 'Advertise Deal', $message);
        send_sms($to_user->phone, $message);


        $msg = "Thank You, You bit" .$request->amount.$deal->currency->name." ";
        send_email(Auth::user()->email, Auth::user()->name, 'Advertise Create Successful', $message);
        send_sms(Auth::user()->phone, $msg);

        return redirect("user/deal/$trans_id");
    }

    public function dealView($id)
    {
        $add = AdvertiseDeal::where('trans_id', $id)->first();

        if (isset($add))
        {
            $price = $add->price;

            return view('user_panel.deal_confirm', compact('add', 'price'));
        }else{
            return back();
        }


    }

    public function dealSendMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.jpg';
            $location = 'assets/images/attach/' . $filename;
            Image::make($image)->save($location);
            $in['image'] = $filename;
        }
        $in['deal_detail'] = $request->message;
        $in['deal_id'] = $request->id;
        $in['type'] = 1;

        $data = DealConvertion::create($in);
        return response()->json($data);
    }

    public function dealSendMessageReply(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
        ]);


        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().'.jpg';
            $location = 'assets/images/attach/' . $filename;
            Image::make($image)->save($location);
            $in['image'] = $filename;
        }
        $in['deal_detail'] = $request->message;
        $in['deal_id'] = $request->id;
        $in['type'] = 2;

        $data = DealConvertion::create($in);
        return response()->json($data);
    }

    public function notiReply($id)
    {
        $add = AdvertiseDeal::where('trans_id', $id)->first();
        $price = $add->price;
        return view('user_panel.deal_reply', compact('add', 'price'));
    }

    public function confirmPaid(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);


        $general = GeneralSettings::first();
        $add = AdvertiseDeal::findOrFail($request->status);
        $price = $add->price;

        $charge = ($add->coin_amount * $general->trx_charge)/100;

        $bal = round(($add->usd_amount/$price)-$charge,8);

        if ($add->add_type == 1){
            $user = User::findOrFail($add->from_user_id);
            $user_adress = UserCryptoBalance::where('user_id', $user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            $new_balance = $user_adress->balance + $bal;
            $user_adress->balance = $new_balance;
            $user_adress->save();

            $to_user = User::findOrFail($add->to_user_id);

            Trx::create([
                'user_id' => $user->id,
                'amount' => $bal .' '.$add->gateway->currency,
                'main_amo' => $new_balance.' '.$add->gateway->currency,
                'charge' => round($charge,8).' '.$add->gateway->currency,
                'type' => '+',
                'title' => 'BUY-'.$add->gateway->currency .'from-'.$to_user->name,
                'trx' => 'BUY'.$add->gateway->currency.time()
            ]);


            $to_user = User::findOrFail($add->to_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();

            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $bal.' '.$add->gateway->currency,
                'main_amo' => $to_user_adress->balance.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '-',
                'title' => 'SELL-'.$add->gateway->currency .'to-'.$user->name,
                'trx' => 'SELL'.$add->gateway->currency.time()
            ]);
        }else{
            $user = User::findOrFail($add->to_user_id);

            $user_adress = UserCryptoBalance::where('user_id', $add->to_user_id)
                ->where('gateway_id', $add->gateway_id)->first();

            $new_balance = $user_adress->balance + round($add->coin_amount - $charge,8);
            $user_adress->balance = $new_balance;
            $user_adress->save();

            $to_user = User::findOrFail($add->to_user_id);

            Trx::create([
                'user_id' => $user->id,
                'amount' => $bal.' '.$add->gateway->currency,
                'main_amo' => $new_balance.' '.$add->gateway->currency,
                'charge' => round($charge,8).' '.$add->gateway->currency,
                'type' => '+',
                'title' => 'BUY-'.$add->gateway->currency .'from-'.$to_user->name,
                'trx' => 'BUY'.$add->gateway->currency.time()
            ]);

            $to_user = User::findOrFail($add->from_user_id);

            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();

            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $bal.' '.$add->gateway->currency,
                'main_amo' => $to_user_adress->balance.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '-',
                'title' => 'SELL-'.$add->gateway->currency .'to-'.$user->name,
                'trx' => 'SELL'.$add->gateway->currency.time()
            ]);
        }

        $add->status = 1;
        $add->save();

        return redirect()->back()->with('message', 'Paid Confirm Complete');
    }

    public function confirmCencel(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);

        $add = AdvertiseDeal::findOrFail($request->status);

        $add->status = 2;
        if ($add->add_type == 1) {
            $to_user = User::findOrFail($add->to_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            $main_bal = $to_user_adress->balance + $add->coin_amount;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();


            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $add->amount_to.' '.$add->gateway->currency,
                'main_amo' => $main_bal.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '+',
                'title' => 'SELL-' . $add->gateway->currency . 'Cancel',
                'trx' => 'SELL' . $add->gateway->currency . time()
            ]);


        }else{
            $to_user = User::findOrFail($add->from_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            $main_bal = $to_user_adress->balance + $add->coin_amount;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();


            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $add->amount_to.' '.$add->gateway->currency,
                'main_amo' => $main_bal.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '+',
                'title' => 'SELL-' . $add->gateway->currency . 'Cancel',
                'trx' => 'SELL' . $add->gateway->currency . time()
            ]);
        }
        $add->save();

        return redirect()->back()->with('message', 'Cancel Complete');
    }

    public function openTrade()
    {
        $title = "Open Trade & Advertisements";
        $addvertise = AdvertiseDeal::where('from_user_id', Auth::id())->where('status', 0)->paginate(10);
        return view('user_panel.trade_history', compact('addvertise', 'title'));
    }

    public function closeTrade()
    {
        $title = "Close Trade ";
        $addvertise = AdvertiseDeal::where('from_user_id', Auth::id())->where('status', 2)->paginate(10);
        return view('user_panel.trade_history', compact('addvertise', 'title'));
    }

    public function completeTrade()
    {
        $title = "Complete Trade ";
        $addvertise = AdvertiseDeal::where('to_user_id', Auth::id())->where('status', 1)->paginate(10);
        return view('user_panel.trade_history', compact('addvertise', 'title'));
    }

    public function cancelTrade()
    {
        $title = "Canceled Trade";
        $addvertise = AdvertiseDeal::where('to_user_id', Auth::id())->where('status', 2)->paginate(10);
        return view('user_panel.trade_history', compact('addvertise', 'title'));
    }

    public function cancelTradeReverce(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);

        $add = AdvertiseDeal::findOrFail($request->status);

        $add->status = 2;
        if ($add->add_type == 1) {
            $to_user = User::findOrFail($add->to_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            $main_bal = $to_user_adress->balance + $add->coin_amount;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();


            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $add->amount_to.' '.$add->gateway->currency,
                'main_amo' => $main_bal.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '+',
                'title' => 'CANCEL-' . $add->gateway->currency . 'Cancel',
                'trx' => 'CANCEL' . $add->gateway->currency . time()
            ]);


        }else{
            $to_user = User::findOrFail($add->from_user_id);
            $to_user_adress = UserCryptoBalance::where('user_id', $to_user->id)
                ->where('gateway_id', $add->gateway_id)->first();
            $main_bal = $to_user_adress->balance + $add->coin_amount;
            $to_user_adress->balance = $main_bal;
            $to_user_adress->save();


            Trx::create([
                'user_id' => $to_user->id,
                'amount' => $add->amount_to.' '.$add->gateway->currency,
                'main_amo' => $main_bal.' '.$add->gateway->currency,
                'charge' => 0,
                'type' => '+',
                'title' => 'CANCEL' . $add->gateway->currency . 'Cancel',
                'trx' => 'CANCEL' . $add->gateway->currency . time()
            ]);
        }
        $add->save();

        return redirect()->back()->with('message', 'Cancel Complete');
    }

    public function paidTradeReverce(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);

        $add = AdvertiseDeal::findOrFail($request->status);
        $add->status = 9;
        $add->save();

        return redirect()->back()->with('message', 'Paid Wait For Seller Approval');
    }

    public function depHistory()
    {
        $title = "Deposit History";
        $trans = Deposit::where('user_id', Auth::id())->where('status', 1)->latest()->paginate(5);
        return view('user_panel.deposit_history', compact('title', 'trans'));
    }

    public function transHistory()
    {
        $title = "Transaction History";
        $trans = Trx::where('user_id', Auth::id())->latest()->paginate(5);
        return view('user_panel.trans_history', compact('title', 'trans'));
    }
    
    public function twoFactorIndex()
    {
        $gnl = GeneralSettings::first();

        $google2fa = new Google2FA();
        $prevcode = Auth::user()->secretcode;
        $secret = $google2fa->generateSecretKey();

        $google2fa->setAllowInsecureCallToGoogleApis(true);
        
        $qrCodeUrl = $google2fa->getQRCodeGoogleUrl(
            $gnl->sitename,
            Auth::user()->email,
            $secret
        );

        $prevqr = $google2fa->getQRCodeGoogleUrl($gnl->sitename,
            Auth::user()->email,
            $prevcode);

        return view('user_panel.two_factor', compact('secret','qrCodeUrl','prevcode','prevqr'));
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request,[
                'code' => 'required',
            ]);
            
        $user = User::find(Auth::id());
        $google2fa = app('pragmarx.google2fa');
        $secret = $request->input('code');
        $valid = $google2fa->verifyKey($user->secretcode, $secret);
        

        if ($valid)
        {
            $user = User::find(Auth::id());
            $user['tauth'] = 0;
            $user['tfver'] = 1;
            $user['secretcode'] = '0';
            $user->save();

           $message =  'Google Two Factor Authentication Disabled Successfully';
           send_email($user['email'],$user['name'] ,'Google 2FA', $message);


           $sms =  'Google Two Factor Authentication Disabled Successfully';
           send_sms($user->mobile, $sms);

            return back()->with('message', 'Two Factor Authenticator Disable Successfully');
        }
        else
        {
            return back()->with('alert', 'Wrong Verification Code');
        }

    }

    public function create2fa(Request $request)
    {
        $user = User::find(Auth::id());
        $this->validate($request,[
                'key' => 'required',
                'code' => 'required',
            ]);


        $google2fa = app('pragmarx.google2fa');
        $secret = $request->input('code');
        $valid = $google2fa->verifyKey($request->key, $secret);
        
        if($valid)
        {
            $user['secretcode'] = $request->key;
            $user['tauth'] = 1;
            $user['tfver'] = 1;
            $user->save();

            $message ='Google Two Factor Authentication Enabled Successfully';
            send_email($user['email'],$user['name'],'Google 2FA', $message);


            $sms =  'Google Two Factor Authentication Enabled Successfully';
            send_sms($user->mobile, $sms);

            return back()->with('message', 'Google Authenticator Enabeled Successfully');
        }
        else
        {
            return back()->with('alert', 'Wrong Verification Code');
        }

    }

}
