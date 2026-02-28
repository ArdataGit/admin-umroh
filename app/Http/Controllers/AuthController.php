<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HistoryAction;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.signin', ['title' => 'Sign In']);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Authentication',
                'action' => 'Login',
                'keterangan' => 'User berhasil login'
            ]);

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            HistoryAction::create([
                'user_id' => Auth::id(),
                'menu' => 'Authentication',
                'action' => 'Logout',
                'keterangan' => 'User berhasil logout'
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/signin');
    }
}
