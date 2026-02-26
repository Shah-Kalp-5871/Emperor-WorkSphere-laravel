<?php

namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Models\User;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(
        protected Employee $model,
        protected User $userModel
    ) {}

    public function getAll(int $perPage = 15)
    {
        return $this->model->with(['user', 'department', 'designation'])->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['user', 'department', 'designation'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Auto-generate employee_code if not provided
            if (empty($data['employee_code'])) {
                $lastId = $this->model->max('id') ?? 0;
                $data['employee_code'] = 'EMP-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
            }

            // First create the User for login
            $user = $this->userModel->create([
                'name' => $data['name'] ?? 'New Employee', // Placeholder name
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? Str::random(10)),
            ]);
            
            // Assign employee role
            $user->assignRole('employee');

            // Then create the Employee record
            return $this->model->create([
                'user_id' => $user->id,
                'department_id' => $data['department_id'] ?? null,
                'designation_id' => $data['designation_id'] ?? null,
                'employee_code' => $data['employee_code'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'date_of_joining' => $data['date_of_joining'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'created_by' => auth()->id(), // Admin ID (supports both guards now)
            ]);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $employee = $this->findById($id);
            
            // Update User details if provided
            if (isset($data['name']) || isset($data['email'])) {
                $employee->user->update([
                    'name' => $data['name'] ?? $employee->user->name,
                    'email' => $data['email'] ?? $employee->user->email,
                ]);
            }

            // Update Employee record
            $employee->update($data);

            return $employee;
        });
    }

    public function delete(int $id)
    {
        $employee = $this->findById($id);
        
        // Due to soft deletes, we delete the employee. 
        // Note: The User will still exist unless we also soft delete users (currently users don't have SoftDeletes trait in your schema).
        // Let's just delete the employee profile for now.
        return $employee->delete();
    }

    public function findByCode(string $code)
    {
        return $this->model->with(['user', 'department', 'designation'])->where('employee_code', $code)->firstOrFail();
    }

    public function getArchived(int $perPage = 15)
    {
        return $this->model->onlyTrashed()->with(['user', 'department', 'designation'])->paginate($perPage);
    }

    public function restore(int $id)
    {
        $employee = $this->model->onlyTrashed()->findOrFail($id);
        $employee->restore();
        return $employee;
    }

    public function forceDelete(int $id)
    {
        $employee = $this->model->onlyTrashed()->findOrFail($id);
        return $employee->forceDelete();
    }
}
