<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function notauthorized()
    {
        // if the user is not logged in, just spit out "not logged in"
        // if the user is logged in, spit out their information, and that they are logged in
        if (Auth::check()) {
            return response()->json([
                'message' => 'You are logged in',
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'message' => 'You are not logged in',
        ]);
    }
}
