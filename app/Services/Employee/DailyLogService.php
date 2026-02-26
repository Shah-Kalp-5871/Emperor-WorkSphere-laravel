<?php

namespace App\Services\Employee;

use App\Repositories\Contracts\DailyLogRepositoryInterface;
use App\Models\Employee;
use Exception;

class DailyLogService
{
    public function __construct(
        protected DailyLogRepositoryInterface $dailyLogRepository
    ) {}

    /**
     * Get logs for current employee
     */
    public function getMyLogs(int $perPage = 15)
    {
        $employeeId = $this->getCurrentEmployeeId();
        return $this->dailyLogRepository->getByEmployeeId($employeeId, $perPage);
    }

    /**
     * Submit a new daily log
     */
    public function submitMyLog(array $data)
    {
        $data['employee_id'] = $this->getCurrentEmployeeId();
        $data['status'] = 'submitted';
        
        // Ensure only one log per day per employee? 
        // For now, let's just create it.
        return $this->dailyLogRepository->create($data);
    }

    /**
     * Get a specific log for the current employee
     */
    public function getMyLogById(int $id)
    {
        $employeeId = $this->getCurrentEmployeeId();
        $log = $this->dailyLogRepository->findById($id);

        if ($log->employee_id !== $employeeId) {
            throw new Exception("Unauthorized to view this daily log.");
        }

        return $log;
    }

    /**
     * Update an existing log
     */
    public function updateMyLog(int $id, array $data)
    {
        $employeeId = $this->getCurrentEmployeeId();
        $log = $this->dailyLogRepository->findById($id);

        if ($log->employee_id !== $employeeId) {
            throw new Exception("Unauthorized to update this daily log.");
        }

        if ($log->status === 'approved') {
            throw new Exception("Cannot update an approved daily log.");
        }

        return $this->dailyLogRepository->update($id, $data);
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
