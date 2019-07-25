<?php

namespace App\Http\Controllers;

use App\Crypto;
use App\Currency;
use App\Ticket;
use App\Trx;
use App\User;
use App\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\GeneralSettings;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

	public function __construct()
    {
		$Gset = GeneralSettings::first();
		$this->sitename = $Gset->sitename;
	}

	public function dashboard()
    {
		$data['page_title'] = 'DashBoard';
        $data['Gset'] = GeneralSettings::first();
        $data['user'] = User::count();
        $data['method'] = Crypto::where('status',1)->count();
        $data['currency'] = Currency::where('status',1)->count();
        $data['user'] = User::count();
        $data['user_active'] = User::where('status', 1)->count();
        $data['email_active'] = User::where('email_verify', 0)->count();
        $data['phone_active'] = User::where('phone_verify', 0)->count();
        $data['ticket'] = Ticket::where('status',1)->orWhere('status' ,3)->count();
        $data['active_today'] = UserLogin::whereDate('created_at', Carbon::today())->count();


        $now = Carbon::today()->addDays(7);
        $play =  Trx::whereYear('created_at', '<=', $now->format('Y'))->get()->groupBy(function($d) {
            return $d->created_at->format('d') ;
        });

        $data['monthly_play'] = [];

        $js = '';

        foreach ($play as $key => $value) {
            $js .= collect([
                    'y' => $key,
                    'a' => $value->count()
                ])->toJson() . ',';

        }

        $data['monthly_play'] = '[' . $js . ']';

		return view('admin.dashboard', $data);
	}



	public function logout()
    {
		Auth::guard('admin')->logout();
		session()->flash('message', 'Just Logged Out!');
		return redirect('/admin');
	}
















}
