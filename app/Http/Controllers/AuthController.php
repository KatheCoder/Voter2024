<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private const ADMIN_EMAILS = ['katlego.majatladi@gmail.com', 'steven@plus94.co.za', 'shumani@plus94.co.za'];

    public function showLoginForm(): Factory|View|Application
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (in_array($request->email, self::ADMIN_EMAILS)) {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/dashboard/A');
        }

        return back()->withErrors(['loginError' => 'Invalid email or password.']);
    }

    public function logout(Request $request): Redirector|Application|RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
