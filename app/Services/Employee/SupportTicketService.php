<?php

namespace App\Services\Employee;

use App\Repositories\Contracts\SupportTicketRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SupportTicketService
{
    public function __construct(
        protected SupportTicketRepositoryInterface $ticketRepo
    ) {}

    public function getEmployeeTickets(array $filters = [], int $perPage = 15)
    {
        $employee = Auth::user()->employee;
        if (!$employee) return collect([]); 

        $filters['employee_id'] = $employee->id;
        return $this->ticketRepo->getAll($perPage, $filters);
    }

    public function getTicketById(int $id)
    {
        $ticket = $this->ticketRepo->findById($id);
        
        // Security check: ensure the ticket belongs to the authenticated employee
        if ($ticket->employee_id !== Auth::user()->employee->id) {
            return null;
        }

        return $ticket;
    }

    public function createTicket(array $data)
    {
        $employee = Auth::user()->employee;
        if (!$employee) throw new \Exception('Employee record not found.');

        $data['employee_id'] = $employee->id;
        $data['status'] = 'open';
        
        return $this->ticketRepo->create($data);
    }

    public function addReply(int $ticketId, string $message)
    {
        $ticket = $this->getTicketById($ticketId);
        if (!$ticket) return null;

        return $this->ticketRepo->addReply($ticketId, [
            'employee_id' => Auth::user()->employee->id,
            'message' => $message,
            'is_internal_note' => false
        ]);
    }
}
