<?php

namespace App\Repositories\Contracts;

interface ProjectRepositoryInterface
{
    public function getArchived(int $perPage = 15);
    public function restore(int $id);
    public function getAll(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function assignEmployees(int $projectId, array $employeeIds);
}
