<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('payment-processed', function () {
    return true;
});

Broadcast::channel('transaction-completed', function () {
    return true;
});

Broadcast::channel('transaction-failed', function () {
    return true;
});

Broadcast::channel('transaction-refunded', function () {
    return true;
});