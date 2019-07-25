<?php

namespace App\Http\Controllers;

use App\AdvertiseDeal;
use App\Trx;
use foo\bar;
use Illuminate\Http\Request;
use App\Deposit;



class DepositController extends Controller
{
    
      public function index()
    {
    	$deposits = Deposit::where('status', 1)->orderBy('id', 'desc')->paginate(10);
    	$page_title = "Deposit  Log";

    	return view('admin.deposit.deposits', compact('deposits','page_title'));
    }

    public function transLog()
    {
        $trans = Trx::orderBy('id', 'desc')->paginate(10);
        $page_title = "Transaction  Log";
        return view('admin.trans_log', compact('trans', 'page_title'));
    }

    public function dealLog()
    {
        $trans = AdvertiseDeal::orderBy('id', 'desc')->paginate(10);
        $page_title = "Deal  Log";
        return view('admin.deal_log', compact('trans', 'page_title'));
    }

    public function dealView($trans_id)
    {
        $trans = AdvertiseDeal::where('trans_id', $trans_id)->first();
        $page_title = "Deal View";

        if ($trans == ''){
            return back();
        }

        return view('admin.deal_view', compact('trans', 'page_title'));
    }

    public function dealSearch(Request $request)
    {
        $trans = AdvertiseDeal::where('trans_id', $request->trans_id)->first();
        $page_title = "Deal View";

        if ($trans == ''){
            return back()->with('alert','Not Found');
        }

        return view('admin.deal_view', compact('trans', 'page_title'));
    }

}
