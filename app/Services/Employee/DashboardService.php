<?php

namespace App\Services\Employee;

use App\Models\AuditLog;
use App\Models\DailyLog;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Get dashboard data for the authenticated employee.
     */
    public function getDashboardData(): array
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return [
                'error' => 'Employee record not found.'
            ];
        }

        $today = Carbon::today();

        // 1. Stats
        $assignedProjectsCount = $employee->projects()->count();
        $openTasksCount = $employee->tasks()->where('status', '!=', 'completed')->count();
        $totalLogsThisMonth = $employee->dailyLogs()
            ->whereMonth('log_date', Carbon::now()->month)
            ->count();
        $overdueTasksCount = $employee->tasks()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', $today)
            ->count();

        // 2. Greeting Summary
        $todayLog = $employee->dailyLogs()->whereDate('log_date', $today)->first();
        
        // 3. Tasks
        $recentTasks = $employee->tasks()
            ->with('project')
            ->latest('assigned_at')
            ->limit(5)
            ->get();

        // 4. Projects
        $recentProjects = $employee->projects()
            ->withCount(['members', 'tasks'])
            ->latest('joined_at')
            ->limit(3)
            ->get()
            ->map(function ($project) {
                $totalTasks = $project->tasks_count;
                $completedTasks = Task::where('project_id', $project->id)
                    ->where('status', 'completed')
                    ->count();

                $project->progress = $totalTasks > 0 
                    ? round(($completedTasks / $totalTasks) * 100) 
                    : 0;
                return $project;
            });

        // 5. Activity (Audit logs + Task assignments)
        $activities = $this->getRecentActivities($employee);

        return [
            'greeting' => [
                'name' => $user->name,
                'pending_tasks' => $openTasksCount,
                'log_submitted_today' => !!$todayLog,
            ],
            'stats' => [
                'projects_assigned' => $assignedProjectsCount,
                'open_tasks' => $openTasksCount,
                'logs_this_month' => $totalLogsThisMonth,
                'overdue_tasks' => $overdueTasksCount,
            ],
            'tasks' => $recentTasks,
            'projects' => $recentProjects,
            'activities' => $activities,
            'today_log' => $todayLog,
        ];
    }

    /**
     * Get recent activities relevant to the employee.
     */
    protected function getRecentActivities(Employee $employee, int $limit = 7): array
    {
        // For simplicity, we can fetch audit logs where this employee was the auditable subject 
        // OR where they were the user who performed the action.
        // However, for an "Employee Feed", they usually want to see things happening TO them.
        
        return AuditLog::where(function($query) use ($employee) {
            $query->where(function($q) use ($employee) {
                $q->where('user_type', 'App\Models\User')
                  ->where('user_id', $employee->user_id);
            })->orWhere(function($q) use ($employee) {
                $q->where('auditable_type', 'App\Models\Employee')
                  ->where('auditable_id', $employee->id);
            });
        })
        ->latest()
        ->limit($limit)
        ->get()
        ->map(function ($log) {
            return [
                'description' => $this->formatActivityDescription($log),
                'created_at' => $log->created_at->diffForHumans(),
                'event' => $log->event,
            ];
        })->toArray();
    }

    protected function formatActivityDescription(AuditLog $log): string
    {
        $type = strtolower(str_replace('App\\Models\\', '', $log->auditable_type));
        
        switch ($log->event) {
            case 'created':
                return "A new {$type} was created.";
            case 'updated':
                return "Your {$type} was updated.";
            case 'assigned':
                return "A new task was assigned to you.";
            default:
                return "An action was performed on {$type}.";
        }
    }
}
