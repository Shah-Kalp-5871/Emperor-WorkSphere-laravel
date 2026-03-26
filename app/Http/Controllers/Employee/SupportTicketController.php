<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupportTicketResource;
use App\Models\SupportTicket;
use App\Services\Employee\SupportTicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketService $ticketService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status']);
        $tickets = $this->ticketService->getEmployeeTickets($filters);
        
        // Return as plain JSON array for simplicity
        $data = collect($tickets->items())->map(fn($t) => [
            'id'            => $t->id,
            'ticket_number' => $t->ticket_number,
            'subject'       => $t->subject,
            'description'   => $t->description,
            'category'      => $t->category,
            'priority'      => $t->priority,
            'status'        => $t->status,
            'created_at'    => optional($t->created_at)->toISOString(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $data,
            'meta'    => [
                'total'        => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page'    => $tickets->lastPage(),
                'from'         => $tickets->firstItem(),
                'to'           => $tickets->lastItem(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $ticket = $this->ticketService->getTicketById($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket not found.'], 404);
        }

        $replies = $ticket->replies->map(fn($r) => [
            'id'          => $r->id,
            'message'     => $r->message,
            'is_admin'    => !is_null($r->admin_id),
            'sender_name' => $r->admin_id ? ($r->admin->name ?? 'Admin') : ($r->employee->user->name ?? 'Employee'),
            'created_at'  => optional($r->created_at)->toISOString(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'subject'       => $ticket->subject,
                'description'   => $ticket->description,
                'category'      => $ticket->category,
                'priority'      => $ticket->priority,
                'status'        => $ticket->status,
                'created_at'    => optional($ticket->created_at)->toISOString(),
                'replies'       => $replies,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category'    => 'required|string',
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:low,medium,high,urgent',
        ]);

        try {
            $ticket = $this->ticketService->createTicket($validated);
            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully.',
                'data'    => ['id' => $ticket->id, 'ticket_number' => $ticket->ticket_number],
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function reply(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate(['message' => 'required|string']);
        $reply = $this->ticketService->addReply($id, $validated['message']);

        if (!$reply) {
            return response()->json(['success' => false, 'message' => 'Unable to add reply.'], 403);
        }

        return response()->json(['success' => true, 'message' => 'Reply added successfully.']);
    }

    public function stats(): JsonResponse
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return response()->json(['success' => true, 'data' => ['total' => 0, 'open' => 0, 'in_progress' => 0, 'resolved' => 0]]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total'       => SupportTicket::where('employee_id', $employee->id)->count(),
                'open'        => SupportTicket::where('employee_id', $employee->id)->where('status', 'open')->count(),
                'in_progress' => SupportTicket::where('employee_id', $employee->id)->where('status', 'in_progress')->count(),
                'resolved'    => SupportTicket::where('employee_id', $employee->id)->whereIn('status', ['resolved', 'closed'])->count(),
            ],
        ]);
    }

    public function formData(): JsonResponse
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return response()->json(['success' => true, 'data' => ['projects' => [], 'tasks' => []]]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $employee->projects()->get(['projects.id', 'name']),
                'tasks'    => $employee->tasks()->get(['tasks.id', 'title']),
            ],
        ]);
    }
}
