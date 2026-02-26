<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        protected Task $model
    ) {}

    public function getAll(int $perPage = 15)
    {
        return $this->model->with(['project:id,name', 'assignees.user'])->latest()->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['project', 'assignees'])->findOrFail($id);
    }

    public function getByProjectId(int $projectId, int $perPage = 15)
    {
        return $this->model->where('project_id', $projectId)
            ->with('assignees')
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['created_by'] = auth('api')->id();
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->findById($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id)
    {
        $task = $this->findById($id);
        return $task->delete();
    }

    public function assignEmployees(int $taskId, array $employeeIds)
    {
        $task = $this->findById($taskId);
        // Sync the pivot table
        $task->assignees()->sync($employeeIds);
        return $task;
    }
}
