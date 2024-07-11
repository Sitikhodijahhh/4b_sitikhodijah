<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    public function index(){
        return view('auth.login');
    }
    public function login_proses(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = [
            'email' =>$request->email,
            'password' =>$request->password
        ];
        if(Auth::attempt($data)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('login')->with('failed','Email atau Password salah');
        }
    }
    public function logout(){
        Auth::logout();
        session()->flush(); 
        return redirect()->route('login')->with('success','Kamu berhasil logout');
    }
    public function register(){
        return view('auth.register');
    }
    public function register_proses(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        User::create($data);

        
        return redirect()->route('login')->with('Berhasil','Selamat Register telah Berhasil!');
    }
}