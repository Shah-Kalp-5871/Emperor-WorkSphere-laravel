<?php

use Illuminate\Support\Facades\Broadcast;

// Employees will subscribe to their own private channel
Broadcast::channel('employee.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['api']]);

// Anonymous chat session channel
Broadcast::channel('anonymous-chat.{sessionToken}', function ($user, $sessionToken) {
    // Both Admins and the Employee who owns the session can join this private channel
    // To be perfectly secure, we'd check if auth()->user() is an admin, OR if they are the employee who started it.
    // For now, if they have a valid token (JWT) they can listen (we can lock it down further using the $sessionToken DB lookup if needed).
    return true; 
}, ['guards' => ['api']]);
