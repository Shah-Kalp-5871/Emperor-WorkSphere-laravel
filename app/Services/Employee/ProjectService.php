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
    public function getMyProjects(int $perPage = 15)
    {
        $employeeId = $this->getCurrentEmployeeId();
        return $this->projectRepository->getAll($perPage, ['employee_id' => $employeeId]);
    }

    /**
     * Get project details for employee
     */
    public function getMyProjectDetails(int $id)
    {
        $employeeId = $this->getCurrentEmployeeId();
        $project = $this->projectRepository->findById($id);

        if (!$project->members->contains($employeeId)) {
            throw new Exception("You are not a member of this project.");
        }

        return $project;
    }

    /**
     * Create a new project as an employee
     */
    public function createProject(array $data)
    {
        $employeeId = $this->getCurrentEmployeeId();

        return \Illuminate\Support\Facades\DB::transaction(function () use ($data, $employeeId) {
            $project = $this->projectRepository->create($data);

            // Prepare members with roles for syncing
            $syncData = [
                $employeeId => ['role' => 'lead']
            ];

            if (!empty($data['employee_ids'])) {
                foreach ($data['employee_ids'] as $id) {
                    if ($id != $employeeId) {
                        $syncData[$id] = ['role' => 'member'];
                    }
                }
            }

            // Sync all members at once
            $project->members()->sync($syncData);

            return $project->load(['members.user', 'tasks']);
        });
    }

    /**
     * Update project members (only if lead)
     */
    /**
     * Add a new member to the project
     */
    public function addMember(int $id, int $employeeId)
    {
        $project = $this->projectRepository->findById($id);

        // Authorization: Admin or existing member
        $currentUser = auth()->user();
        $isAdmin = auth('admin')->check();
        
        $isMember = false;
        if ($currentUser && $currentUser->employee) {
            $isMember = $project->members()->where('employee_id', $currentUser->employee->id)->exists();
        }

        if (!$isAdmin && !$isMember) {
            throw new Exception("You are not authorized to add members to this project.");
        }

        // Validation: Active employee
        $employee = \App\Models\Employee::find($employeeId);
        if (!$employee || !$employee->is_active) {
            throw new Exception("Selected employee is not active or does not exist.");
        }

        // Validation: No duplicates
        $alreadyMember = $project->members()->where('employee_id', $employeeId)->exists();
        if ($alreadyMember) {
            throw new Exception("This employee is already a member of the project.");
        }

        return $this->projectRepository->addMember($id, $employeeId, 'member');
    }

    public function updateMembers(int $id, array $employeeIds)
    {
        $currentEmployeeId = $this->getCurrentEmployeeId();
        $project = $this->projectRepository->findById($id);

        // Verify authorization: only project leads can manage members
        $isLead = $project->members()->where('employee_id', $currentEmployeeId)
            ->wherePivot('role', 'lead')
            ->exists();

        if (!$isLead) {
            throw new Exception("You are not authorized to manage members for this project.");
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($project, $employeeIds, $currentEmployeeId) {
            // Prepare members with roles for syncing
            // The current lead MUST remain a lead
            $syncData = [
                $currentEmployeeId => ['role' => 'lead']
            ];

            foreach ($employeeIds as $eId) {
                if ($eId != $currentEmployeeId) {
                    $syncData[$eId] = ['role' => 'member'];
                }
            }

            // Sync all members
            $project->members()->sync($syncData);

            return $project->load(['members.user', 'tasks']);
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
