<?php

namespace App\Services\Employee;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Models\Employee;
use Exception;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Get projects assigned to current employee
     */
    public function getMyProjects()
    {
        $employeeId = $this->getCurrentEmployeeId();
        // ProjectRepository has getAll, let's see if it supports employee_id filter
        // If not, we might need to add it or use a different approach
        return $this->projectRepository->getAll(100, ['employee_id' => $employeeId]);
    }

    /**
     * Get project details for employee
     */
    public function getMyProjectDetails(int $id)
    {
        $employeeId = $this->getCurrentEmployeeId();
        $project = $this->projectRepository->findById($id);

        if (!$project->employees->contains($employeeId)) {
            throw new Exception("You are not a member of this project.");
        }

        return $project;
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
