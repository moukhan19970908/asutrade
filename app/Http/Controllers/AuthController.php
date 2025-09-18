<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Проверяем, существует ли пользователь
        $user = User::where('email', $credentials['email'])->first();
        Log::info('user',['user' => $user,'password' => $user->password]);
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Вручную авторизуем пользователя
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            return redirect()->route('profile.index');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
