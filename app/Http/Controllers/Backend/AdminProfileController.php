<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Cache\RateLimiting\Unlimited;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminProfileController extends Controller
{
  public function AdminProfile(){
    
      $adminData = Admin::find(3);
      
      return view('admin.admin_profile_view', compact('adminData'));

  }



  public function AdminProfileEdit(){
    $editData = Admin::find(3);
      
    return view('admin.admin_profile_edit', compact('editData'));

  }


  public function AdminProfileStore(Request $request){
    $data=Admin::find(3);
    $data->name =$request->name;
    $data->email =$request->email;

    if($request->file('profile_photo_path')){
        $file = $request->file('profile_photo_path');
        
        //para borrar la imagen antigua
        @unlink(public_path('upload/admin_images/'.$data->profile_photo_path));
        
        $filename = date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('upload/admin_images'), $filename);
        $data['profile_photo_path']= $filename;
    }
    $data->save();
    $notification = array(
      'message' =>"Admin profile uploaded sucessfully",
      'alert-type'=>'success'
    );

    return redirect()->route('admin.profile')->with($notification);

  }

  public function AdminChangePassword(){
    return view('admin.admin_change_password');

  }

  public function AdminUpdateChangePassword(Request $request){
    $validateData = $request->validate([
      'oldpassword' => 'required',
      'password' => 'required|confirmed'
    ]);

    $hashedPassword = Admin::find(3)->password;

    if(Hash::check($request->oldpassword, $hashedPassword)){
      $admin = Admin::find(3);
      $admin->password = Hash::make($request->password);
      $admin->save();
      Auth::logout();

      Return redirect()->route('admin.logout');

    }else{

      return redirect()->back();
      


  }
}
}
