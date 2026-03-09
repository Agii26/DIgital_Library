<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{
    public function show(Request $request)
    {
        $user = User::where('email', $request->email)
            ->where('set_password_token', $request->token)
            ->where('set_password_token_expires_at', '>', now())
            ->firstOrFail();

        return view('auth.set-password', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('set_password_token', $request->token)
            ->where('set_password_token_expires_at', '>', now())
            ->firstOrFail();

        $user->update([
            'password'                      => bcrypt($request->password),
            'password_set'                  => true,
            'set_password_token'            => null,
            'set_password_token_expires_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Password set successfully! You can now login.');
    }
}