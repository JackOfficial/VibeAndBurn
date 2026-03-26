<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        // Redirect Admins to the Admin Dashboard
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return redirect()->intended('/admin/dashboard');
        }

        // Redirect regular Users to the Home page
        return redirect()->intended('/home');
    }
}