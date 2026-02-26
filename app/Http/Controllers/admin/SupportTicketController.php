<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\SupportTicketResource;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'employee_id']);
        $tickets = $this->ticketService->listTickets($filters);
        return SupportTicketResource::collection($tickets);
    }

    public function show(int $id)
    {
        $ticket = $this->ticketService->getTicketById($id);
        return new SupportTicketResource($ticket);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:open,resolved',
        ]);

        $ticket = $this->ticketService->updateStatus($id, $validated['status']);
        return response()->json(['message' => 'Ticket status updated successfully', 'data' => new SupportTicketResource($ticket)]);
    }

    public function reply(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean'
        ]);

        $reply = $this->ticketService->replyToTicket($id, $validated['message'], $validated['is_internal'] ?? false);
        return response()->json(['message' => 'Reply added successfully', 'data' => $reply]);
    }
}
