<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gateway;
use Image;
use Carbon\Carbon;
use App\GeneralSettings;
use Illuminate\Support\Facades\Input;
class GatewayController extends Controller
{
    public function show()
        {
        	$gateways = Gateway::all();


        	$first_gateway = Gateway::first();
            
            if(is_null($gateways))
            {
                $default=[
                    'gateimg' => 'paypal.png',
                    'name' => 'PayPal',
                    'minamo' => '100',
                    'maxamo' => '100000',
                    'fixed_charge' => '10',
                    'percent_charge' => '11',
                    'rate' => '21',
                    'val1' => 'JHuiqejhkjq',
                    'val2' => '24897HHd',
                    'status' => '1'
                ];

                Gateway::create($default);
                $gateways = Gateway::all();
            }       
        	$page_title = "Payment Methods";
        	return view('admin.deposit.gateway', compact('gateways','first_gateway','page_title'));

        }

        public function update(Request $request)
        {
            $gateway = Gateway::all();

            $this->validate($request, [
                'val1' => 'required',
                'val2' => 'required',
            ]);

            foreach ($gateway as $data)
            {
                Gateway::whereId($data->id)
                    ->update([
                        'val1' => $request->val1,
                        'val2' => $request->val2,
                    ]);
            }

            return back()->with('success','Gateway Information Updated Successfully');

        }
}
