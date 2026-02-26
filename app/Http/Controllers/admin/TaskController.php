<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignEmployeesRequest;
use App\Http\Requests\Admin\Tasks\StoreTaskRequest;
use App\Http\Requests\Admin\Tasks\UpdateTaskRequest;
use App\Services\Admin\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index()
    {
        return \App\Http\Resources\TaskResource::collection($this->taskService->listTasks());
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        return new \App\Http\Resources\TaskResource($task);
    }

    public function show(int $id)
    {
        return new \App\Http\Resources\TaskResource($this->taskService->getTaskById($id));
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        return response()->json(['message' => 'Task updated successfully', 'data' => $task]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteTask($id);
        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function assignEmployees(AssignEmployeesRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->assignEmployees($id, $request->validated('employee_ids'));
        return response()->json(['message' => 'Employees assigned to task successfully. Notifications triggered.', 'data' => $task]);
    }
}
