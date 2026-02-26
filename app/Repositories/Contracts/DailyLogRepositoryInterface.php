<?php

namespace App\Repositories\Contracts;

interface DailyLogRepositoryInterface
{
    public function getAll(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByEmployeeId(int $employeeId, int $perPage = 15);
}
