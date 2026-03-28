<?php

namespace App\Observers;

use App\Models\User;
use App\Models\wallet;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Automatically create a wallet for every new user.
     */
    public function created(User $user)
    {
        wallet::create([
            'user_id' => $user->id,
            'money'   => 0,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user)
    {
        // Usually used for logging profile changes
    }

    /**
     * Handle the User "deleted" event.
     * If you use Soft Deletes, this will trigger.
     */
    public function deleted(User $user)
    {
        // Optional: You could freeze the wallet here if using soft deletes
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user)
    {
        // Ensure the wallet exists upon restoration
        if (!$user->wallet) {
            wallet::create([
                'user_id' => $user->id,
                'money'   => 0,
            ]);
        }
    }

    /**
     * Handle the User "force deleted" event.
     * Permanent removal from the database.
     */
    public function forceDeleted(User $user)
    {
        // Manually clean up the wallet if your migration doesn't have onDelete('cascade')
        if ($user->wallet) {
            $user->wallet()->delete();
        }
    }
}