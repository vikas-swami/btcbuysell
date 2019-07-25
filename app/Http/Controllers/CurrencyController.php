<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crypto = Currency::orderBy('id','desc')->paginate(15);
        $page_title = 'Manage Currency';
        return view('admin.currency.index', compact('crypto', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'usd_rate' => 'required|numeric|min:0',
        ]);

        if ($request->status != null){
            $status = 1;
        }else{
            $status = 0;
        }

        Currency::create([
           'name' => $request->name,
           'usd_rate' => $request->usd_rate,
           'status' => $status,
        ]);

        return back()->with('success', 'Create Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        $page_title = 'Update Currency';
        $crypto = Currency::findOrFail($currency->id);
        return view('admin.currency.edit', compact('crypto', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $this->validate($request,[
            'name' => 'required',
            'usd_rate' => 'required|numeric|min:0',
        ]);

        if ($request->status != null){
            $status = 1;
        }else{
            $status = 0;
        }

        Currency::where('id',$currency->id)->update([
           'name' => $request->name,
           'usd_rate' => $request->usd_rate,
           'status' => $status,
        ]);
        return redirect('admin/currency')->with('success', 'Update Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        
    }
}
