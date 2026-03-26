<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected SupportTicket $ticket,
        protected string $status,
        protected ?string $note = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = str_replace('_', ' ', strtoupper($this->status));
        $mail = (new MailMessage)
            ->subject("Support Ticket Update: #{$this->ticket->ticket_number}")
            ->line("Your support ticket status has been updated to: **{$statusLabel}**.")
            ->line("Ticket Subject: {$this->ticket->subject}");

        if ($this->note) {
            $mail->line("Resolution Note: {$this->note}");
        }

        return $mail->action('View Ticket', url("/employee/tickets"))
            ->line('Thank you for contacting support.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'status' => $this->status,
            'subject' => $this->ticket->subject,
            'note' => $this->note,
            'message' => "Your ticket #{$this->ticket->ticket_number} status is now " . str_replace('_', ' ', $this->status) . "."
        ];
    }
}
