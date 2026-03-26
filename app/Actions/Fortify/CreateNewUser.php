<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
   public function create(array $input)
{
    Validator::make($input, [
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
        ],
        'password' => $this->passwordRules(),
    ])->validate();

    // 1. Prepare the user data
    $data = [
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => Hash::make($input['password']),
    ];

    // Check for referral link
    if (session()->has('sharedLinkID')) {
        $data['linkOwner'] = session()->get('sharedLinkID');
    }

    // 2. Create the user instance
    $user = User::create($data);

    // 3. Assign the default 'User' role
    // This ensures every Fortify registration gets the role automatically
    $user->assignRole('User');

    return $user;
}
}
