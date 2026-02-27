<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        protected Task $model
    ) {}

    public function getAll(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['project', 'assignees.user', 'creator'])->latest();

        if (!empty($filters['employee_id'])) {
            $query->whereHas('assignees', function($q) use ($filters) {
                $q->where('employee_id', $filters['employee_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function getArchived(int $perPage = 15)
    {
        return $this->model->onlyTrashed()->with(['project', 'creator'])->latest('deleted_at')->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['project', 'assignees.user', 'creator'])->findOrFail($id);
    }

    public function getByProjectId(int $projectId, int $perPage = 15)
    {
        return $this->model->where('project_id', $projectId)->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['created_by'] = auth('api')->id() ?? auth('admin')->id();
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->findById($id);
        $task->update($data);
        return $task->fresh(['project', 'assignees.user', 'creator']);
    }

    public function updateStatus(int $id, string $status)
    {
        $task = $this->findById($id);
        $task->update(['status' => $status]);
        return $task->fresh(['project', 'assignees.user', 'creator']);
    }

    public function delete(int $id)
    {
        $task = $this->findById($id);
        return $task->delete(); // soft delete
    }

    public function restore(int $id)
    {
        $task = $this->model->onlyTrashed()->findOrFail($id);
        $task->restore();
        return $task;
    }

    public function assignEmployees(int $taskId, array $employeeIds)
    {
        $task = $this->findById($taskId);
        $task->assignees()->sync($employeeIds);
        return $task->fresh(['project', 'assignees.user', 'creator']);
    }
}
