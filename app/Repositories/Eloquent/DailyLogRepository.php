<?php

namespace App\Repositories\Eloquent;

use App\Models\DailyLog;
use App\Repositories\Contracts\DailyLogRepositoryInterface;

class DailyLogRepository implements DailyLogRepositoryInterface
{
    public function __construct(
        protected DailyLog $model
    ) {}

    public function getAll(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['employee.user', 'reviewer'])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('log_date', $filters['date']);
        }

        if (!empty($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id)
    {
        return $this->model->with(['employee.user', 'reviewer'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $log = $this->findById($id);
        $log->update($data);
        return $log;
    }

    public function delete(int $id)
    {
        $log = $this->findById($id);
        return $log->delete();
    }

    public function getByEmployeeId(int $employeeId, int $perPage = 15)
    {
        return $this->model->where('employee_id', $employeeId)->latest()->paginate($perPage);
    }
}
