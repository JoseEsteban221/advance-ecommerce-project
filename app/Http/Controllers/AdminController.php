<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function Index(){
        return view('auth.admin_login');
    }

    public function Dashboard(){
        return view ('admin.index');
    }

    public function Login(Request $request){
        $check = $request->all();

        if(auth::guard('admin')->attempt([
            'email'=>$check['email'],
            'password'=>$check['password']
            ])){

                return redirect()->route('admin.dashboard')->with('error', 'Login succesfully');
            }else
                return back()->with('error', "Plase, login again");
    }

    public function logout(){

        Auth::guard('admin')->logout();
        return redirect()->route('login_form')->with('error', 'Logout Sucessfully');


    }

    public function register(){
        return view('admin.admin_register');
    }

    public function AdminRegisterCreate(Request $request){
       
        Admin::insert([
            'name'=> $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at'=> Carbon::now()
        ]);

        return redirect()->route('login_form')->with('error', 'Login Sucessfully!!');

    }
}
