<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialController extends Controller
{

public function redirect()
{
    return Socialite::driver('google')->redirect();
}

public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Log in existing user
            Auth::login($user);
            return redirect('/home');
        } else {
            // 1. Prepare data for the new user
            $userData = [
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password'  => Hash::make(Str::random(24)),
                'avatar'    => $googleUser->getAvatar(),
            ];

            // Add referral link if it exists in session
            if (session()->has('sharedLinkID')) {
                $userData['linkOwner'] = session()->get('sharedLinkID');
            }

            // 2. Create the user
            $newUser = User::create($userData);

            // 3. ASSIGN DEFAULT ROLE
            // This works because you added 'use HasRoles' to your User model
            $newUser->assignRole('User');

            // 4. Log in and redirect
            Auth::login($newUser);
            return redirect('/home');
        }
    } catch (\Exception $e) {
        // In production, log this instead of dd()
        return redirect('/login')->with('error', 'Google authentication failed.');
    }
  }

}