<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\DailyLogRepositoryInterface;

class DailyLogService
{
    public function __construct(
        protected DailyLogRepositoryInterface $dailyLogRepo
    ) {}

    public function listLogs(array $filters = [], int $perPage = 15)
    {
        return $this->dailyLogRepo.getAll($perPage, $filters);
    }

    public function getLogById(int $id)
    {
        return $this->dailyLogRepo.findById($id);
    }

    public function reviewLog(int $id, array $data)
    {
        $reviewData = [
            'status' => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? null,
            'reviewed_by' => auth('api')->id(),
            'reviewed_at' => now(),
        ];

        return $this->dailyLogRepo.update($id, $reviewData);
    }

    public function deleteLog(int $id)
    {
        return $this->dailyLogRepo.delete($id);
    }
}
