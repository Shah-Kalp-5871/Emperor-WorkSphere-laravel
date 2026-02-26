<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\AnonymousMessageSent;
use App\Http\Controllers\Controller;
use App\Models\AnonymousChatMessage;
use App\Models\AnonymousChatSession;
use Illuminate\Http\Request;

class AnonymousChatController extends Controller
{
    /**
     * View all active anonymous sessions
     */
    public function index()
    {
        // Don't return expired sessions
        $sessions = AnonymousChatSession::where('expires_at', '>', now())
            ->orderBy('last_seen_at', 'desc')
            ->get();

        return response()->json($sessions);
    }

    /**
     * Admin replying to an anonymous session
     */
    public function reply(Request $request, $sessionToken)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $session = AnonymousChatSession::where('session_token', $sessionToken)->firstOrFail();

        $chatMessage = AnonymousChatMessage::create([
            'session_id' => $session->id,
            'message' => $request->message,
            'is_admin_reply' => true,
            'admin_id' => auth('api')->id(),
        ]);

        // Broadcast back to the session channel
        broadcast(new AnonymousMessageSent($chatMessage))->toOthers();

        return response()->json(['message' => 'Reply sent.', 'data' => $chatMessage]);
    }

    /**
     * Get messages for a session (Admin view)
     */
    public function getMessages($sessionToken)
    {
        $session = AnonymousChatSession::where('session_token', $sessionToken)->firstOrFail();
        
        $messages = $session->messages()->orderBy('created_at', 'asc')->get();
        // Notice we do NOT decrypt the `encrypted_employee_id` here. Administration sees the alias only.

        return response()->json($messages);
    }
}
