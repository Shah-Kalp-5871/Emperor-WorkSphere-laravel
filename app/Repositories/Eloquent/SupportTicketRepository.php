<?php

namespace App\Repositories\Eloquent;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Repositories\Contracts\SupportTicketRepositoryInterface;

class SupportTicketRepository implements SupportTicketRepositoryInterface
{
    public function __construct(
        protected SupportTicket $model
    ) {}

    public function create(array $data)
    {
        if (empty($data['ticket_number'])) {
            $data['ticket_number'] = 'TKT-' . strtoupper(bin2hex(random_bytes(3)));
        }
        return $this->model->create($data);
    }

    public function getAll(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['employee.user', 'assignee'])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['employee.user', 'assignee', 'replies.admin', 'replies.employee.user'])->findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $ticket = $this->findById($id);
        $ticket->update($data);
        return $ticket;
    }

    public function addReply(int $ticketId, array $data)
    {
        return SupportTicketReply::create([
            'ticket_id' => $ticketId,
            'admin_id' => $data['admin_id'] ?? null,
            'employee_id' => $data['employee_id'] ?? null,
            'message' => $data['message'],
            'is_internal_note' => $data['is_internal_note'] ?? false,
        ]);
    }

    public function getReplies(int $ticketId)
    {
        return SupportTicketReply::where('ticket_id', $ticketId)
            ->with(['admin', 'employee.user'])
            ->oldest()
            ->get();
    }
}
