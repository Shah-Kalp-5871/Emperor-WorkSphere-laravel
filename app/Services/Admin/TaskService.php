<?php

namespace App\Services\Admin;

use App\Events\TaskAssigned;
use App\Events\TaskStatusUpdated;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function listTasks(array $filters = [], int $perPage = 15)
    {
        return $this->taskRepository->getAll($perPage, $filters);
    }

    public function getArchivedTasks(int $perPage = 15)
    {
        return $this->taskRepository->getArchived($perPage);
    }

    public function getTaskById(int $id)
    {
        return $this->taskRepository->findById($id);
    }

    public function createTask(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $task = $this->taskRepository->create($data);

            if (!empty($data['assignee_ids'])) {
                $task = $this->taskRepository->assignEmployees($task->id, $data['assignee_ids']);
                event(new TaskAssigned($task, $data['assignee_ids']));
            }

            Cache::forget('tasks.list.all');
            return ['task' => $task];
        });
    }

    public function updateTask(int $id, array $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $task = $this->taskRepository->update($id, $data);

            if (isset($data['assignee_ids'])) {
                $task = $this->taskRepository->assignEmployees($id, $data['assignee_ids']);
                event(new TaskAssigned($task, $data['assignee_ids']));
            }

            Cache::forget('tasks.list.all');
            return ['task' => $task];
        });
    }

    public function updateTaskStatus(int $id, string $status): array
    {
        return DB::transaction(function () use ($id, $status) {
            $task = $this->taskRepository->findById($id);
            $oldStatus = $task->status;
            
            if ($oldStatus !== $status) {
                $task = $this->taskRepository->updateStatus($id, $status);
                event(new TaskStatusUpdated($task, $oldStatus, $status));
            }

            Cache::forget('tasks.list.all');
            return ['task' => $task];
        });
    }

    public function archiveTask(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $result = $this->taskRepository->delete($id);
            Cache::forget('tasks.list.all');
            return $result;
        });
    }

    public function restoreTask(int $id): mixed
    {
        return DB::transaction(function () use ($id) {
            $task = $this->taskRepository->restore($id);
            Cache::forget('tasks.list.all');
            return $task;
        });
    }

    public function assignEmployees(int $taskId, array $employeeIds): mixed
    {
        return DB::transaction(function () use ($taskId, $employeeIds) {
            $task = $this->taskRepository->assignEmployees($taskId, $employeeIds);
            event(new TaskAssigned($task, $employeeIds));
            return $task;
        });
    }
}
