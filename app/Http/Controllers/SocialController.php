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
            
            // Search for existing user
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                // Link account if Google ID is missing
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                // Security check
                if (in_array($user->status, ['banned', 'suspended'])) {
                    return redirect('/login')->with('error', "Your account is currently {$user->status}.");
                }

                Auth::login($user);
                return $this->redirectUser($user);

            } else {
                // Create New User
                $userData = [
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(24)),
                    'avatar'    => $googleUser->getAvatar(),
                    'status'    => 'active',
                ];

                if (session()->has('sharedLinkID')) {
                    $userData['linkOwner'] = session()->get('sharedLinkID');
                }

                $newUser = User::create($userData);
                
                // Assign default role
                $newUser->assignRole('User');

                Auth::login($newUser);
                
                // Optimized: Use the same redirection logic for new users
                return $this->redirectUser($newUser);
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }
    }

    /**
     * Optimized Redirection Logic
     * Matches the Spatie roles used in your Navbar/UI
     */
    protected function redirectUser($user)
    {
        // 1. Check for Administrative Roles (Admin or User Admin)
        if ($user->hasAnyRole(['Admin', 'User Admin', 'Super Admin'])) {
            return redirect()->intended('/admin/dashboard');
        }

        // 2. Default for standard Users
        return redirect()->intended('/home');
    }
}