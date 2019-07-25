<?php

namespace App\Http\Controllers;

use App\GeneralSettings;
use App\Faq;
use App\Menu;
use App\Slider;
use App\Social;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use File;
use Image;
class WebSettingController extends Controller
{

    public function manageLogo()
    {
        $data['page_title'] = "Manage Logo & Favicon";
        return view('webcontrol.logo', $data);
    }
    public function updateLogo(Request $request)
    {
        $this->validate($request, [
            'logo' => 'mimes:png',
            'favicon' => 'mimes:png',
            'freeloader' => 'mimes:gif'
        ]);
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = 'logo.png';
            $location = 'assets/images/logo/' . $filename;
            Image::make($image)->save($location);
        }
        if ($request->hasFile('favicon')) {
            $image = $request->file('favicon');
            $filename = 'favicon.png';
            $location = 'assets/images/logo/' . $filename;
            Image::make($image)->save($location);
        }
        $notification = array('message' => 'Update Successfully', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function getContact()
    {
        $data['basic'] = GeneralSettings::first();
        $data['page_title'] = "Contact Settings";
        return view('webcontrol.contact-setting',$data);
    }

    public function putContactSetting(Request $request)
    {
        $basic = GeneralSettings::first();
        $request->validate([
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $in = Input::except('_method','_token');
        $basic->fill($in)->save();

        $notification =  array('message' => 'Contact  Updated Successfully', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function manageFooter()
    {
        $data['page_title'] = "Manage Web Footer";
        return view('webcontrol.footer', $data);
    }
    public function updateFooter(Request $request)
    {
        $basic = GeneralSettings::first();
        $this->validate($request,[
            'copyright' => 'required'
        ]);
        $in = Input::except('_method','_token');
        $basic->fill($in)->save();
        $notification = array('message' => 'Web Footer Updated Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }

    public function manageSocial()
    {
        $data['page_title'] = "Manage Social";
        $data['social'] = Social::all();
        return view('webcontrol.social', $data);
    }
    public function storeSocial(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'code' => 'required',
            'link' => 'required',
        ]);
        $product = Social::create($request->input());
        return response()->json($product);
    }
    public function editSocial($product_id)
    {
        $product = Social::find($product_id);
        return response()->json($product);
    }
    public function updateSocial(Request $request,$product_id)
    {
        $product = Social::find($product_id);
        $product->name = $request->name;
        $product->code = $request->code;
        $product->link = $request->link;
        $product->save();
        return response()->json($product);
    }
    public function deleteSocial($product_id)
    {
        $product = Social::destroy($product_id);
        return response()->json($product);
    }

    public function manageMenu()
    {
        $data['page_title'] = "Control Menu";
        $data['menus'] = Menu::paginate(2);
        return view('webcontrol.menu-show',$data);
    }
    public function createMenu()
    {
        $data['page_title'] = "Create Menu";
        return view('webcontrol.menu-create',$data);
    }
    public function storeMenu(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:menus,name',
            'description' => 'required'
        ]);
        $in = Input::except('_method','_token');
        $in['slug'] = str_slug($request->name);
        Menu::create($in);
        $notification = array('message' => 'Menu Created Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }
    public function editMenu($id)
    {
        $data['page_title'] = "Edit Menu";
        $data['menu'] = Menu::findOrFail($id);
        return view('webcontrol.menu-edit',$data);
    }
    public function updateMenu(Request $request,$id)
    {
        $menu = Menu::findOrFail($id);
        $this->validate($request,[
            'name' => 'required|unique:menus,name,'.$menu->id,
            'description' => 'required'
        ]);
        $in = Input::except('_method','_token');
        $in['slug'] = str_slug($request->name);
        $menu->fill($in)->save();
        $notification = array('message' => 'Menu Updated Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }
    public function deleteMenu(Request $request)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        Menu::destroy($request->id);
        $notification = array('message' => 'Menu Deleted Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }


    public function manageSlider()
    {
        $data['page_title'] = "Manage Slider";
        $data['slider'] = Slider::find(5);
        return view('webcontrol.slider', $data);
    }
    public function storeSlider(Request $request)
    {
        $this->validate($request,[
            'image' => 'mimes:png,jpeg,jpg'
        ]);

        $s =  Slider::find(5);

        $in = Input::except('_method','_token');
        if($request->hasFile('image')){
            @unlink('assets/images/slider/' . $s->image);
            $image = $request->file('image');
            $filename = 'slider_'.time().'.jpg';
            $location = 'assets/images/slider/' . $filename;
            Image::make($image)->resize(1920,799)->save($location);
            $in['image'] = $filename;
        }
        Slider::whereId(5)->update($in);

        $notification = array('message' => 'Slider Update Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }
    public function deleteSlider(Request $request)
    {
        $this->validate($request,[
            'id' => 'required'
        ]);
        $slider = Slider::findOrFail($request->id);
        File::delete('assets/images/slider/'.$slider->image);
        $slider->delete();

        $notification = array('message' => 'Slider Deleted Successfully.', 'alert-type' => 'success');
        return back()->with($notification);
    }




}
