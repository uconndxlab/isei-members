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

    public function login(Request $request)
    {
        // Validate the incoming request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();
    
            // Log the successful login attempt
            \Log::info('User logged in: ' . $request->input('email'));
    
            // Redirect to intended URL or fallback to /admin
            return redirect()->intended('/admin');
        }
    
        // Log the failed authentication attempt
        \Log::warning('Failed login attempt for email: ' . $request->input('email'));
    
        // Return back with an error message
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
