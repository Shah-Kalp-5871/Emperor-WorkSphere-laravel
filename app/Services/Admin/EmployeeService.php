<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Exception;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository
    ) {}

    public function listEmployees(int $perPage = 15)
    {
        return $this->employeeRepository->getAll($perPage);
    }

    public function getEmployeeById(int $id)
    {
        return $this->employeeRepository->findById($id);
    }

    public function createEmployee(array $data)
    {
        try {
            $employee = $this->employeeRepository->create($data);
            
            // TODO: Dispatch Job or Event to send email with generated password
            Log::info("Employee user created: {$data['email']} with generic password.");
            
            return $employee;
        } catch (Exception $e) {
            Log::error('Failed to create employee: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateEmployee(int $id, array $data)
    {
        try {
            return $this->employeeRepository->update($id, $data);
        } catch (Exception $e) {
            Log::error("Failed to update employee {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteEmployee(int $id)
    {
        try {
            return $this->employeeRepository->delete($id);
        } catch (Exception $e) {
            Log::error("Failed to delete employee {$id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function getArchivedEmployees(int $perPage = 15)
    {
        return $this->employeeRepository->getArchived($perPage);
    }

    public function restoreEmployee(int $id)
    {
        return $this->employeeRepository->restore($id);
    }

    public function permanentlyDeleteEmployee(int $id)
    {
        return $this->employeeRepository->forceDelete($id);
    }

    public function totalDeleteEmployee(int $id)
    {
        try {
            return $this->employeeRepository->totalDelete($id);
        } catch (Exception $e) {
            Log::error("Failed to totally delete employee {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}
