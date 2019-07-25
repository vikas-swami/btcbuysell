<?php

namespace App\Http\Controllers;

use App\Crypto;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CryptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = 'Manage Payment Methods';
        $crypto = Crypto::orderBy('id', 'desc')->get();
        return view('admin.crypto.index', compact('crypto', 'page_title'));
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
            'icon' => 'required|image|mimes:png,jpg,jpeg',
        ]);


        if ($request->status != null){
            $status = 1;
        }else{
            $status = 0;
        }

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $filename = time() . '.' . 'png';
            $location = 'assets/images/crypto/'. $filename;
            Image::make($image)->save($location);
            $image_name =  $filename;

            Crypto::create([
                'name' => $request->name,
                'icon' => $image_name,
                'status' => $status,
            ]);
            return back()->with('success', 'Create Successfully!');
        }else{
            return back()->with('alert', 'Having Some Problem With Your Icon!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Crypto  $crypto
     * @return \Illuminate\Http\Response
     */
    public function show(Crypto $crypto)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crypto  $crypto
     * @return \Illuminate\Http\Response
     */
    public function edit(Crypto $crypto)
    {
        $page_title = 'Update  Payment Method';
        return view('admin.crypto.edit', compact('crypto', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Crypto  $crypto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crypto $crypto)
    {
        $this->validate($request,[
            'name' => 'required',
            'icon' => 'image|mimes:png,jpg,jpeg',

        ]);

        if ($request->status != null){
            $status = 1;
        }else{
            $status = 0;
        }

        if ($request->hasFile('icon')) {
            @unlink('assets/images/crypto/'. $crypto->icon);
            $image = $request->file('icon');
            $filename = time() . '.' . 'png';
            $location = 'assets/images/crypto/' . $filename;
            Image::make($image)->save($location);
            $image_name = $filename;
        }else{
            $image_name =  $crypto->icon;
        }

        Crypto::whereId($crypto->id)->update([
            'name' => $request->name,
            'icon' => $image_name,
            'status' => $status,
        ]);

        return redirect('admin/crypto')->with('success', 'Update Successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crypto  $crypto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crypto $crypto)
    {
        @unlink('assets/images/crypto/'. $crypto->icon);
         $crypto->delete();
        return back()->with('success', 'Delete Successfully!');
    }
}
