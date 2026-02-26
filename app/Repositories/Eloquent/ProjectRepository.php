<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        protected Project $model
    ) {}

    public function getAll(int $perPage = 15)
    {
        return $this->model->with(['creator', 'members.user', 'tasks'])->latest()->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['members', 'tasks'])->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['created_by'] = auth('api')->id();
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $project = $this->findById($id);
        $project->update($data);
        return $project;
    }

    public function delete(int $id)
    {
        $project = $this->findById($id);
        return $project->delete();
    }

    public function assignEmployees(int $projectId, array $employeeIds)
    {
        $project = $this->findById($projectId);
        // Sync the pivot table
        $project->members()->sync($employeeIds);
        return $project;
    }
}
