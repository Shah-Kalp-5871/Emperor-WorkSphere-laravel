<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    public function listProjects(int $perPage = 15)
    {
        return \Illuminate\Support\Facades\Cache::remember('projects.list.page.' . request('page', 1), 300, function () use ($perPage) {
            return $this->projectRepository->getAll($perPage);
        });
    }

    public function getProjectById(int $id)
    {
        return $this->projectRepository->findById($id);
    }

    public function createProject(array $data)
    {
        try {
            $project = $this->projectRepository->create($data);
            \Illuminate\Support\Facades\Cache::tags(['projects'])->flush();
            return $project;
        } catch (Exception $e) {
            Log::error('Failed to create project: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateProject(int $id, array $data)
    {
        try {
            $project = $this->projectRepository->update($id, $data);
            \Illuminate\Support\Facades\Cache::tags(['projects'])->flush();
            return $project;
        } catch (Exception $e) {
            Log::error("Failed to update project {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProject(int $id)
    {
        try {
            $deleted = $this->projectRepository->delete($id);
            \Illuminate\Support\Facades\Cache::tags(['projects'])->flush();
            return $deleted;
        } catch (Exception $e) {
            Log::error("Failed to delete project {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function assignEmployees(int $projectId, array $employeeIds)
    {
        try {
            $project = $this->projectRepository->assignEmployees($projectId, $employeeIds);
            \Illuminate\Support\Facades\Cache::tags(['projects'])->flush();
            return $project;
        } catch (Exception $e) {
            Log::error("Failed to assign employees to project {$projectId}: " . $e->getMessage());
            throw $e;
        }
    }
}
