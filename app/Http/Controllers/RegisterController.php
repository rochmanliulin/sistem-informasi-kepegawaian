<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request; 
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'username' => 'required|max:255|min:5',
            'email' => 'required|email:dns|max:255|unique:users,email',
            'password' => 'required|min:8|max:255',
            'terms' => 'required'
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.min' => 'Username minimal harus mengandung :min karakter!',
            'username.max' => 'Username maksimal harus mengandung :max karakter!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Format :attribute tidak valid!',
            'email.unique' => 'Username maksimal harus mengandung :max karakter!',
            'email.max' => 'Email maksimal harus mengandung :max karakter!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.min' => 'Password minimal harus mengandung :min karakter!',
            'password.max' => 'Password maksimal harus mengandung :max karakter!',
            'terms.required' => 'Centang dahulu :attribute and conditions!'
        ]);
        
        User::create($attributes);

        return redirect('/login')->with('success', 'Berhasil registrasi, lanjutkan sign in yaa...');
    }
}
