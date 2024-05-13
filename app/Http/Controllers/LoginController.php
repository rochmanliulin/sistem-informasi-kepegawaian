<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:dns'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format :attribute tidak valid!',
            'password.required' => 'Password tidak boleh kosong!'
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // return redirect()->intended('dashboard');

            /// Redirect berdasarkan peran pengguna menggunakan gates
            if (Gate::allows('isEditorOrAdmin', $user)) {
                return redirect()->intended('dashboard');
            } else {
                return redirect('/user/salary');
            }
        }

        return back()->with('error', 'Email atau Password Anda salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
