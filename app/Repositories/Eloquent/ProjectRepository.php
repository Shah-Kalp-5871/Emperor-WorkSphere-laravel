<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        protected Project $model
    ) {}

    public function getAll(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['creator', 'members.user', 'tasks.assignees.user'])->latest();

        if (!empty($filters['employee_id'])) {
            $query->whereHas('members', function($q) use ($filters) {
                $q->where('employee_id', $filters['employee_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function getArchived(int $perPage = 15)
    {
        return $this->model->onlyTrashed()->with(['creator'])->latest('deleted_at')->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['creator', 'members.user', 'tasks.assignees.user'])->findOrFail($id);
    }

    public function create(array $data)
    {
        // Only set created_by if an admin is authenticated, otherwise it's null (for employees)
        $data['created_by'] = auth('admin')->id();
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $project = $this->findById($id);
        $project->update($data);
        return $project->fresh(['members.user', 'tasks']);
    }

    public function delete(int $id)
    {
        $project = $this->findById($id);
        return $project->delete(); // soft delete
    }

    public function restore(int $id)
    {
        $project = $this->model->onlyTrashed()->findOrFail($id);
        $project->restore();
        return $project;
    }

    public function assignEmployees(int $projectId, array $employeeIds)
    {
        $project = $this->findById($projectId);
        $project->members()->sync($employeeIds);
        return $project->fresh(['members.user', 'tasks']);
    }

    public function addMember(int $projectId, int $employeeId, string $role = 'member')
    {
        $project = $this->findById($projectId);
        $project->members()->syncWithoutDetaching([
            $employeeId => ['role' => $role]
        ]);
        return $project->fresh(['members.user']);
    }
}
