<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function getAll(int $perPage = 15);
    public function findById(int $id);
    public function getByProjectId(int $projectId, int $perPage = 15);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function assignEmployees(int $taskId, array $employeeIds);
}
