<?php

namespace App\Http\Controllers\Api\Employee;

use App\Events\AnonymousMessageSent;
use App\Http\Controllers\Controller;
use App\Models\AnonymousChatMessage;
use App\Models\AnonymousChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class AnonymousChatController extends Controller
{
    /**
     * Start a new anonymous session.
     */
    public function startSession(Request $request)
    {
        $employeeId = auth('api')->id();
        
        // Generate a random alias like "Anonymous Wolf"
        $adjectives = ['Silent', 'Hidden', 'Phantom', 'Shadow', 'Secret', 'Anonymous'];
        $nouns = ['Wolf', 'Eagle', 'Panther', 'Falcon', 'Fox', 'Tiger'];
        $alias = $adjectives[array_rand($adjectives)] . ' ' . $nouns[array_rand($nouns)] . ' ' . rand(100, 999);

        $session = AnonymousChatSession::create([
            'session_token' => Str::random(60),
            'anonymous_alias' => $alias,
            'last_seen_at' => now(),
            'expires_at' => now()->addHours(24), // Session lives for 24h
        ]);

        return response()->json([
            'message' => 'Anonymous session started.',
            'session_token' => $session->session_token,
            'alias' => $alias,
        ], 201);
    }

    /**
     * Send message as employee
     */
    public function sendMessage(Request $request, $sessionToken)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        $session = AnonymousChatSession::where('session_token', $sessionToken)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Encrypt the employee ID so the DB admins cannot casually read who it is.
        // It uses Laravel's APP_KEY. Only the application code can decrypt it if strictly necessary.
        $encryptedEmployeeId = Crypt::encryptString((string) auth('api')->id());

        $chatMessage = AnonymousChatMessage::create([
            'session_id' => $session->id,
            'encrypted_employee_id' => $encryptedEmployeeId,
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        // Extend session expiry
        $session->update([
            'last_seen_at' => now(),
            'expires_at' => now()->addHours(24),
        ]);

        // Broadcast to admin and the employee themselves
        broadcast(new AnonymousMessageSent($chatMessage))->toOthers();

        return response()->json(['message' => 'Message sent anonymously.', 'data' => $chatMessage], 201);
    }

    /**
     * Get messages for a session
     */
    public function getMessages($sessionToken)
    {
        $session = AnonymousChatSession::where('session_token', $sessionToken)->firstOrFail();
        
        // Only return non-sensitive fields
        $messages = $session->messages()->select('id', 'session_id', 'message', 'is_admin_reply', 'created_at')->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
}
