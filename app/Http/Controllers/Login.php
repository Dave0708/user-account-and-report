<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{   
    public function index() {   
        return view("login");
    }

    public function login(Request $request) {

        $validation = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Try to authenticate using username
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // If failed, return with error
        return back()->with('failed', "Wrong credentials!")
                    ->withInput($request->only('username'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
}
