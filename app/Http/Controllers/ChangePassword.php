<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class ChangePassword extends Controller
{

    protected $user;

    public function __construct()
    {
        // Logout user saat mengakses halaman change password
        Auth::logout();

        // Dinonaktifkan sementara karena route:list error jika aktif
        // Validasi signature URL untuk security
        // $request = request();
        // if (!URL::hasValidSignature($request)) {
        //     abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        // }

        // Ambil ID dari URL untuk mencari user
        $id = intval(request()->id);
        $this->user = User::find($id);

        // // // Jika user tidak ditemukan
        // if (!$this->user) {
        //     abort(404, 'USER NOT FOUND');
        // }
    }

    public function show()
    {
        return view('auth.change-password', [ 'email' => $this->user->email ]);
    }
    
    public function update(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:5'],
            'confirm-password' => ['same:password']
        ]);

        // Cari user berdasarkan email
        $existingUser = User::where('email', $attributes['email'])->first();
        if ($existingUser) {
            // Update password
            $existingUser->update([
                'password' => $attributes['password']
            ]);
            return redirect('/login')->with('success', 'Password berhasil diubah');
        } else {
            return back()->with('error', 'Email Anda tidak cocok dengan email yang meminta reset password');
        }
    }
}
