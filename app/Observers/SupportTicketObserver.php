<?php

namespace App\Observers;

use App\Models\SupportTicket;
use App\Notifications\TicketStatusUpdated;

class SupportTicketObserver
{
    /**
     * Handle the SupportTicket "updated" event.
     */
    public function updated(SupportTicket $supportTicket): void
    {
        if ($supportTicket->isDirty('status') && $supportTicket->employee && $supportTicket->employee->user) {
            $newStatus = $supportTicket->status;
            
            // Get the latest reply if it was just added (for resolution note)
            $latestReply = $supportTicket->replies()
                ->where('admin_id', '!=', null)
                ->latest()
                ->first();

            $note = ($newStatus === 'resolved' && $latestReply) ? $latestReply->message : null;

            $supportTicket->employee?->user?->notify(
                new TicketStatusUpdated($supportTicket, $newStatus, $note)
            );
        }
    }
}
