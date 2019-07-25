<?php

namespace App\Http\Controllers;

use App\Deposit;

use App\Trx;
use App\UserCryptoBalance;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Gateway;
use App\GeneralSettings;


use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Token;
use Stripe\Charge;
use App\Lib\coinPayments;
use CoinGate\CoinGate;
use App\Lib\BlockIo;
use App\Lib\CoinPaymentHosted;

class PaymentController extends Controller
{

    public function userDataUpdate($data)
    {
        if($data->status == 0)
        {
            $data['status'] = 1;
            $data->update();

            $user = User::findOrFail($data->user_id);

            $address = UserCryptoBalance::where('user_id', $data->user_id)
                ->where('gateway_id',$data->gateway_id)->first();

            $new_balance = $address->balance + $data->amount;
            $address->balance = $new_balance;
            $address->save();

            Trx::create([
                'user_id' => $user->id,
                'amount' => $data->amount,
                'main_amo' => $new_balance,
                'charge' => 0,
                'type' => '+',
                'title' => 'Deposit Via' . $data->gateway->name,
                'trx' => $data->trx
            ]);

            $txt = $data->amount . ' ' . $data->gateway->currency .' Deposited Successfully Via '. $data->gateway->name;
            notify($user, 'Deposit Successfully Completed', $txt);
        }

    }

    public function depositConfirm(Request $request)
    {
        $this->validate($request,[
                'amount' => 'required|numeric|min:0',
                'gateway' => 'required',
            ]);

        $gate = Gateway::findOrFail($request->gateway);

        $all = file_get_contents("https://api.coinmarketcap.com/v2/ticker/");
        $ticker = json_decode($all, true);

        $btc_usd = $ticker['data'][1]['quotes']['USD']['price'];
        $lite_usd = $ticker['data'][2]['quotes']['USD']['price'];
        $doge_usd = $ticker['data'][74]['quotes']['USD']['price'];
        $eth_usd = $ticker['data'][1027]['quotes']['USD']['price'];

        $de['user_id'] = Auth::id();
        $de['gateway_id'] = $gate->id;
        $de['amount'] = floatval($request->amount);
        $de['charge'] = 0;
        $de['usd_amo'] = null;
        $de['btc_amo'] = 0;
        $de['status'] = 0;
        $de['trx'] = 'DP-'.rand();
        $data = Deposit::create($de);


        if(is_null($data))
        {
            return redirect()->route('deposit')->with('alert', 'Invalid Deposit Request');
        }
        if ($data->status != 0)
        {
            return redirect()->route('deposit')->with('alert', 'Invalid Deposit Request');
        }


        if($data->gateway_id == 505){

            $method = Gateway::find(505);

            if($data->btc_amo == 0 || $data->btc_wallet=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.btc');

                $req = array(
                    'amount' => $btc_usd,
                    'currency1' => 'USD',
                    'currency2' => 'BTC',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') {

                    $bcoin = $request->amount;
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();

                } else {
                    return back()->with('alert', 'Failed to Process');
                }

            }
            $wallet = $data['btc_wallet'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=bitcoin:$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaybtc', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->gateway_id == 506)
        {

            $method = Gateway::find(506);
            if($data->btc_amo == 0 ||$data->btc_wallet == "")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.eth');

                $req = array(
                    'amount' => $eth_usd,
                    'currency1' => 'USD',
                    'currency2' => 'ETH',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok'){
                    $bcoin = $request->amount;
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();

                }else{
                    return back()->with('alert', 'Failed to Process');
                }

            }

            $wallet = $data['btc_wallet'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpayeth', compact('bcoin','wallet','qrurl','page_title'));

        }elseif($data->gateway_id == 509)
        {

            $method = Gateway::find(509);
            if($data->btc_amo==0 ||$data->btc_wallet=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.doge');

                $req = array(
                    'amount' => $doge_usd,
                    'currency1' => 'USD',
                    'currency2' => 'DOGE',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok')
                {
                    $bcoin = $request->amount;
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();

                }
                else
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }

            $wallet = $data['btc_wallet'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaydoge', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->gateway_id == 510)
        {

            $method = Gateway::find(510);
            if($data->btc_amo==0 ||$data->btc_wallet=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.ltc');

                $req = array(
                    'amount' => $lite_usd,
                    'currency1' => 'USD',
                    'currency2' => 'LTC',
                    'custom' => $data->trx,
                    'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok')
                {
                    $bcoin = $request->amount;
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_wallet'] = $sendadd;
                    $data->update();

                }
                else
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }

            $wallet = $data['btc_wallet'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpayltc', compact('bcoin','wallet','qrurl','page_title'));

        }


    }


    //IPN Functions //////


    public function ipnCoinPayBtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx',$track)->orderBy('id', 'DESC')->first();

        if ($status>=100 || $status==2)
        {
            if ($currency2 == "BTC" && $data->status == '0' && $data->btc_amo <= $amount2)
            {
                $this->userDataUpdate($data);
            }
        }
    }

    public function ipnCoinPayEth(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2)
        {
            if ($currency2 == "ETH" && $data->status == '0' && $data->btc_amo<=$amount2)
            {
                $this->userDataUpdate($data);
            }
        }
    }

    public function ipnCoinPayDoge(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2)
        {
            if ($currency2 == "DOGE" && $data->status == '0' && $data->btc_amo<=$amount2)
            {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayLtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = Deposit::where('trx',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2)
        {
            if ($currency2 == "LTC" && $data->status == '0')
            {
                $this->userDataUpdate($data);
            }
        }
    }


}
