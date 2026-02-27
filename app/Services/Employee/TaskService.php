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
        
        $isAssignee = $task->assignees->contains($employeeId);
        $isProjectMember = $task->project && $task->project->members->contains($employeeId);

        // Safety check: ensure task is assigned to this employee OR employee is in the project
        if (!$isAssignee && !$isProjectMember) {
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
        if (!$task->assignees->contains($employeeId)) {
            throw new Exception("Unauthorized to update this task's status. Only assigned employees can update.");
        }

        return $this->taskRepository->updateStatus($taskId, $status);
    }

    /**
     * Create a task as an employee
     */
    public function createMyTask(array $data)
    {
        $employeeId = $this->getCurrentEmployeeId();
        
        // If assigned to a project, verify the creator is a member of that project
        if (!empty($data['project_id'])) {
            $project = \App\Models\Project::with('members')->findOrFail($data['project_id']);
            if (!$project->members->contains('id', $employeeId)) {
                throw new Exception("You can only create tasks in projects you are a member of.");
            }

            // Verify assignees are project members (basic check)
            if (!empty($data['assignee_ids'])) {
                foreach ($data['assignee_ids'] as $aId) {
                    if (!$project->members->contains('id', $aId)) {
                        throw new Exception("Assignee ID {$aId} is not a member of this project.");
                    }
                }
            }
        }
        
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data, $employeeId) {
            $task = $this->taskRepository->create($data);
            
            // Assign to provided employees, otherwise default to self
            $assignees = !empty($data['assignee_ids']) ? $data['assignee_ids'] : [$employeeId];
            $this->taskRepository->assignEmployees($task->id, $assignees);
            
            return $task->load(['project', 'assignees.user']);
        });
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
