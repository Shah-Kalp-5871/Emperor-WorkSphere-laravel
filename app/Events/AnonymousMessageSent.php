<?php

namespace App\Events;

use App\Models\AnonymousChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnonymousMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(AnonymousChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        // Broadcast on a private channel specific to the anonymous session
        return [
            new \Illuminate\Broadcasting\PrivateChannel('anonymous-chat.' . $this->message->session->session_token)
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        // We load what we need
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'is_admin_reply' => $this->message->is_admin_reply,
            'created_at' => $this->message->created_at->toISOString(),
            // Exclude encrypted_employee_id or admin_id to keep anonymity on the wire!
        ];
    }
}
