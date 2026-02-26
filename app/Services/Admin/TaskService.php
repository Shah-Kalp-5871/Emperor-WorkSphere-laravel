<?php

namespace App\Services\Admin;

use App\Events\TaskAssigned;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function listTasks(int $perPage = 15)
    {
        return $this->taskRepository->getAll($perPage);
    }

    public function getTaskById(int $id)
    {
        return $this->taskRepository->findById($id);
    }

    public function createTask(array $data)
    {
        try {
            $task = $this->taskRepository->create($data);
            return $task;
        } catch (Exception $e) {
            Log::error('Failed to create task: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateTask(int $id, array $data)
    {
        try {
            return $this->taskRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error("Failed to update task {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTask(int $id)
    {
        try {
            return $this->taskRepository->delete($id);
        } catch (Exception $e) {
            Log::error("Failed to delete task {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function assignEmployees(int $taskId, array $employeeIds)
    {
        try {
            $task = $this->taskRepository->assignEmployees($taskId, $employeeIds);
            
            // Trigger Application Event
            TaskAssigned::dispatch($task, $employeeIds);
            Log::info("Triggered TaskAssigned event for Task ID: {$taskId}");

            // Trigger Push Notification Job
            $userIds = \App\Models\Employee::whereIn('id', $employeeIds)->pluck('user_id')->toArray();
            if (!empty($userIds)) {
                $payload = [
                    'title' => 'New Task Assigned',
                    'message' => 'You have been assigned to: ' . $task->title,
                    'url' => '/employee/tasks/' . $task->id,
                    'icon' => '/favicon.ico'
                ];
                \App\Jobs\PushNotificationJob::dispatch($userIds, $payload);
            }
            
            return $task;
        } catch (Exception $e) {
            Log::error("Failed to assign employees to task {$taskId}: " . $e->getMessage());
            throw $e;
        }
    }
}
