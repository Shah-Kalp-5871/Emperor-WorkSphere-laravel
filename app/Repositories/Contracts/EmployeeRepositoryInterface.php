<?php

namespace App\Repositories\Contracts;

interface EmployeeRepositoryInterface
{
    public function getAll(int $perPage = 15);
    
    public function findById(int $id);
    
    public function create(array $data);
    
    public function update(int $id, array $data);
    
    public function delete(int $id);
    
    public function findByCode(string $code);

    public function getArchived(int $perPage = 15);

    public function restore(int $id);

    public function forceDelete(int $id);
}
