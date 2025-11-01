<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ])->onlyInput('email');
            }

            // Check if selected role matches user role
            $selectedRole = $request->input('role');
            $roleMatch = false;
            if ($selectedRole === 'director' && $user->isDirector()) {
                $roleMatch = true;
            } elseif ($selectedRole === 'manager' && $user->isManager()) {
                $roleMatch = true;
            } elseif ($selectedRole === 'employee' && $user->isEmployee()) {
                $roleMatch = true;
            }

            if (!$roleMatch) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Selected role does not match your account role.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->isDirector()) {
                return redirect()->route('director.dashboard');
            } elseif ($user->isManager()) {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
        } catch (\Exception $e) {
            // If there's any error (including CSRF), just redirect to login
            return redirect()->route('login')->with('info', 'Session telah berakhir.');
        }
    }
} 