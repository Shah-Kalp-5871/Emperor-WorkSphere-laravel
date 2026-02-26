<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function restore(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('manage_projects');
    }
}
