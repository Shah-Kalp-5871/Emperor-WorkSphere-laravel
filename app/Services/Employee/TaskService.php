<?php

namespace App\Services\Employee;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    /**
     * Get tasks assigned to current employee
     */
    public function getMyTasks(int $perPage = 15)
    {
        $employeeId = $this->getCurrentEmployeeId();
        return $this->taskRepository->getAll($perPage, ['employee_id' => $employeeId]);
    }

    /**
     * Get single task details for employee
     */
    public function getTaskById(int $taskId)
    {
        $employeeId = $this->getCurrentEmployeeId();
        $task = $this->taskRepository->findById($taskId);
        
        // Safety check: ensure task is assigned to this employee
        if (!$task->employees->contains($employeeId)) {
            throw new Exception("Unauthorized to view this task.");
        }

        return $task->load(['project', 'creator']);
    }

    /**
     * Update task status by employee
     */
    public function updateMyTaskStatus(int $taskId, string $status)
    {
        $employeeId = $this->getCurrentEmployeeId();
        
        // Safety check: ensure task is assigned to this employee
        $task = $this->taskRepository->findById($taskId);
        if (!$task->employees->contains($employeeId)) {
            throw new Exception("Unauthorized to update this task's status.");
        }

        return $this->taskRepository->updateStatus($taskId, $status);
    }

    protected function getCurrentEmployeeId()
    {
        $user = auth()->user();
        if (!$user || !$user->employee) {
            throw new Exception("User is not an employee.");
        }
        return $user->employee->id;
    }
}
