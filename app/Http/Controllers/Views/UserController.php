<?php

namespace App\Http\Controllers\Views;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $user = User::get();
        $title = 'Halaman User';
        return view('user', compact('user', 'title'));
    }

    public function view_login(){
        if (session()->get('user')){
            return redirect('dashboard');
        }

        return view('login');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = Auth::user();

            if($user->role != 'superuser'){
                return redirect()->back()->with('error', 'Email Atau Password Anda Salah');
            }

            request()->session()->put('user', Auth::user());
            return redirect('dashboard');
        }

        return redirect()->back()->with('error', 'Email Atau Password Anda Salah');
    }

    public function profile(){

    }

    public function logout(){
        request()->session()->forget('user');
        Auth::logout();

        return redirect('user/login')->with('success', 'Sukses Melakukan Logout');
    }
}
