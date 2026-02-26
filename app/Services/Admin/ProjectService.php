<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function listProjects(array $filters = [], int $perPage = 15)
    {
        // Status filter pushed down to query level
        return $this->projectRepository->getAll($perPage, $filters);
    }

    public function getArchivedProjects(int $perPage = 15)
    {
        return $this->projectRepository->getArchived($perPage);
    }

    public function getProjectById(int $id)
    {
        return $this->projectRepository->findById($id);
    }

    public function createProject(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $project = $this->projectRepository->create($data);

            // Assign members if provided
            if (!empty($data['employee_ids'])) {
                $project = $this->projectRepository->assignEmployees($project->id, $data['employee_ids']);
            }

            Cache::forget('projects.list.all');
            return ['project' => $project, 'members_assigned' => !empty($data['employee_ids'])];
        });
    }

    public function updateProject(int $id, array $data): array
    {
        return DB::transaction(function () use ($id, $data) {
            $project = $this->projectRepository->update($id, $data);

            // Re-sync members if sent
            if (isset($data['employee_ids'])) {
                $project = $this->projectRepository->assignEmployees($id, $data['employee_ids']);
            }

            Cache::forget('projects.list.all');
            return ['project' => $project];
        });
    }

    public function archiveProject(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $result = $this->projectRepository->delete($id);
            Cache::forget('projects.list.all');
            return $result;
        });
    }

    public function restoreProject(int $id): mixed
    {
        return DB::transaction(function () use ($id) {
            $project = $this->projectRepository->restore($id);
            Cache::forget('projects.list.all');
            return $project;
        });
    }

    public function assignEmployees(int $projectId, array $employeeIds): mixed
    {
        return DB::transaction(function () use ($projectId, $employeeIds) {
            return $this->projectRepository->assignEmployees($projectId, $employeeIds);
        });
    }
}

