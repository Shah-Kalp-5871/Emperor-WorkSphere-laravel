<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage_tasks') || class_basename($user) === 'Employee';
    }

    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('manage_tasks') || clone $task->assignees()->where('users.id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_tasks');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('manage_tasks');
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('manage_tasks');
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('manage_tasks');
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('manage_tasks');
    }
}
