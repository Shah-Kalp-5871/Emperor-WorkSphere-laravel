<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getMyTasks();
        return response()->json([
            'success' => true,
            'message' => 'Employee tasks retrieved successfully.',
            'data' => $tasks
        ]);
    }

    /**
     * GET /api/employee/tasks/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            return response()->json([
                'success' => true,
                'message' => 'Task details retrieved successfully.',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * PATCH /api/employee/tasks/{id}/status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        try {
            $task = $this->taskService->updateMyTaskStatus($id, $request->status);
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully.',
                'data' => $task
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
