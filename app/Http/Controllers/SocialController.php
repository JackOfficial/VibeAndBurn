<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
            
            // Check by google_id OR email (safer for users who registered manually first)
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // 1. Update google_id if it's missing (linked account)
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                // 2. SAFETY CHECK: Block Banned or Suspended users
                if (in_array($user->status, ['banned', 'suspended'])) {
                    return redirect('/login')->with('error', 'Your account is currently ' . $user->status . '.');
                }

                Auth::login($user);
                return $this->redirectUser($user);

            } else {
                // 3. Prepare data for the new user
                $userData = [
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(24)),
                    'avatar'    => $googleUser->getAvatar(),
                    'status'    => 'active', // Default status from our enum
                ];

                if (session()->has('sharedLinkID')) {
                    $userData['linkOwner'] = session()->get('sharedLinkID');
                }

                $newUser = User::create($userData);
                $newUser->assignRole('User');

                Auth::login($newUser);
                return redirect('/home');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }
    }

    /**
     * Handle Redirection based on Role
     */
    protected function redirectUser($user)
    {
        // If they were trying to reach a specific admin page, go there
        // Otherwise, redirect based on their role
        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/home');
    }
}