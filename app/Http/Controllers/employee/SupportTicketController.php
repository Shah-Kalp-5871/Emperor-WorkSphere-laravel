<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportTicketResource;
use App\Services\Employee\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status']);
        $tickets = $this->ticketService->getEmployeeTickets($filters);
        return SupportTicketResource::collection($tickets);
    }

    public function show(int $id)
    {
        $ticket = $this->ticketService->getTicketById($id);
        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found or access denied.'
            ], 404);
        }
        return new SupportTicketResource($ticket);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = $this->ticketService->createTicket($validated);
        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully.',
            'data' => new SupportTicketResource($ticket)
        ], 201);
    }

    public function reply(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $reply = $this->ticketService->addReply($id, $validated['message']);
        if (!$reply) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to add reply.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reply added successfully.',
            'data' => $reply
        ]);
    }

    public function stats(): JsonResponse
    {
        $stats = [
            'total' => \App\Models\SupportTicket::where('employee_id', auth()->user()->employee->id)->count(),
            'open' => \App\Models\SupportTicket::where('employee_id', auth()->user()->employee->id)->where('status', 'open')->count(),
            'resolved' => \App\Models\SupportTicket::where('employee_id', auth()->user()->employee->id)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Ticket statistics retrieved successfully.',
            'data' => $stats
        ]);
    }

    public function formData(): JsonResponse
    {
        $employee = auth()->user()->employee;
        
        $projects = $employee->projects()->get(['id', 'name']);
        $tasks = $employee->tasks()->get(['id', 'title']);

        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $projects,
                'tasks' => $tasks
            ]
        ]);
    }
}
