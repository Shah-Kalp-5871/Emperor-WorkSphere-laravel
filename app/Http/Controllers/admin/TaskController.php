<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignEmployeesRequest;
use App\Http\Requests\Admin\Tasks\StoreTaskRequest;
use App\Http\Requests\Admin\Tasks\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\Admin\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * GET /api/admin/tasks
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'project_id', 'search']);
        $perPage = (int) $request->get('per_page', 15);

        $paginator = $this->taskService->listTasks($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data'    => TaskResource::collection($paginator)->response()->getData(true),
        ]);
    }

    /**
     * POST /api/admin/tasks
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $result = $this->taskService->createTask($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data'    => new TaskResource($result['task']),
        ], 201);
    }

    /**
     * GET /api/admin/tasks/{id}
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        return response()->json([
            'success' => true,
            'message' => 'Task detail retrieved.',
            'data'    => new TaskResource($task),
        ]);
    }

    /**
     * PUT /api/admin/tasks/{id}
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $result = $this->taskService->updateTask($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'data'    => new TaskResource($result['task']),
        ]);
    }

    /**
     * PATCH /api/admin/tasks/{id}/status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,on_hold'
        ]);

        $result = $this->taskService->updateTaskStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'data'    => new TaskResource($result['task']),
        ]);
    }

    /**
     * DELETE /api/admin/tasks/{id} — Soft delete (archive).
     */
    public function destroy(int $id): JsonResponse
    {
        $this->taskService->archiveTask($id);

        return response()->json([
            'success' => true,
            'message' => 'Task archived successfully.',
            'data'    => null,
        ]);
    }

    /**
     * GET /api/admin/tasks/archived
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage   = (int) $request->get('per_page', 15);
        $paginator = $this->taskService->getArchivedTasks($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Archived tasks retrieved successfully.',
            'data'    => TaskResource::collection($paginator)->response()->getData(true),
        ]);
    }

    /**
     * POST /api/admin/tasks/{id}/restore
     */
    public function restore(int $id): JsonResponse
    {
        $task = $this->taskService->restoreTask($id);

        return response()->json([
            'success' => true,
            'message' => 'Task restored successfully.',
            'data'    => new TaskResource($task),
        ]);
    }

    /**
     * POST /api/admin/tasks/{id}/assign-employees
     */
    public function assignEmployees(AssignEmployeesRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->assignEmployees($id, $request->validated('employee_ids'));

        return response()->json([
            'success' => true,
            'message' => 'Employees assigned to task successfully.',
            'data'    => new TaskResource($task),
        ]);
    }
}
