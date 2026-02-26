<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\SupportTicketRepositoryInterface;

class SupportTicketService
{
    public function __construct(
        protected SupportTicketRepositoryInterface $ticketRepo
    ) {}

    public function listTickets(array $filters = [], int $perPage = 15)
    {
        return $this->ticketRepo->getAll($perPage, $filters);
    }

    public function getTicketById(int $id)
    {
        return $this->ticketRepo->findById($id);
    }

    public function updateStatus(int $id, string $status)
    {
        return $this->ticketRepo->update($id, ['status' => $status]);
    }

    public function replyToTicket(int $id, string $message, bool $isInternal = false)
    {
        return $this->ticketRepo->addReply($id, [
            'admin_id' => auth('api')->id(),
            'message' => $message,
            'is_internal_note' => $isInternal
        ]);
    }
}
