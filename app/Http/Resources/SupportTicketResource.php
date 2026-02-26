<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'subject' => $this->subject,
            'description' => $this->description,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => $this->status,
            'employee_name' => $this->employee->user->name ?? 'N/A',
            'employee_designation' => $this->employee->designation->name ?? 'N/A',
            'assignee_name' => $this->assignee->name ?? null,
            'resolved_at' => $this->resolved_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'replies' => $this->whenLoaded('replies', function() {
                return $this->replies->map(fn($reply) => [
                    'id' => $reply->id,
                    'message' => $reply->message,
                    'is_admin' => !is_null($reply->admin_id),
                    'sender_name' => $reply->admin_id ? $reply->admin->name : ($reply->employee->user->name ?? 'Employee'),
                    'created_at' => $reply->created_at->toISOString(),
                ]);
            }),
        ];
    }
}
