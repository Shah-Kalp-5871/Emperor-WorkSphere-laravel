<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\DailyLog;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get aggregated statistics for the admin dashboard.
     */
    public function getStats(): array
    {
        $today = Carbon::today();

        // 1. Employee Stats
        $totalEmployees = Employee::count();
        $newEmployeesThisMonth = Employee::whereMonth('created_at', Carbon::now()->month)->count();

        // 2. Project Stats
        $activeProjectsCount = Project::where('status', 'active')->count();
        $inProgressTasksCount = Task::where('status', 'in_progress')->count();

        // 3. Log Stats
        $logsTodayCount = DailyLog::whereDate('log_date', $today)->count();
        $pendingLogsCount = max(0, $totalEmployees - $logsTodayCount);

        // 4. Overdue Tasks Stats
        $overdueTasksCount = Task::where('status', '!=', 'completed')
            ->where('due_date', '<', $today)
            ->count();

        return [
            'employees' => [
                'total' => $totalEmployees,
                'new_this_month' => $newEmployeesThisMonth,
            ],
            'projects' => [
                'active_count' => $activeProjectsCount,
                'tasks_in_progress' => $inProgressTasksCount,
            ],
            'logs' => [
                'today_count' => $logsTodayCount,
                'pending_count' => $pendingLogsCount,
                'total_employees' => $totalEmployees,
            ],
            'tasks' => [
                'overdue_count' => $overdueTasksCount,
            ],
        ];
    }

    /**
     * Get active projects with progress details.
     */
    public function getActiveProjects(int $limit = 5)
    {
        return Project::where('status', 'active')
            ->withCount(['members', 'tasks'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($project) {
                // Calculate progress: (completed tasks / total tasks) * 100
                $totalTasks = $project->tasks_count;
                $completedTasks = Task::where('project_id', $project->id)
                    ->where('status', 'completed')
                    ->count();

                $project->progress = $totalTasks > 0 
                    ? round(($completedTasks / $totalTasks) * 100) 
                    : 0;

                return $project;
            });
    }

    /**
     * Get recent activity from AuditLog.
     */
    public function getRecentActivity(int $limit = 10)
    {
        return AuditLog::with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'event' => $log->event,
                    'user_name' => $log->user?->name ?? 'System',
                    'auditable_type' => str_replace('App\\Models\\', '', $log->auditable_type),
                    'created_at' => $log->created_at->diffForHumans(),
                    'description' => $this->formatActivityDescription($log),
                ];
            });
    }

    /**
     * Get overdue tasks list.
     */
    public function getOverdueTasks(int $limit = 5)
    {
        return Task::where('status', '!=', 'completed')
            ->where('due_date', '<', Carbon::today())
            ->with(['project', 'assignees.user'])
            ->latest('due_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Format a human-readable description for an audit log entry.
     */
    protected function formatActivityDescription(AuditLog $log): string
    {
        $userName = $log->user?->name ?? 'Someone';
        $type = strtolower(str_replace('App\\Models\\', '', $log->auditable_type));
        
        switch ($log->event) {
            case 'created':
                return "{$userName} created a new {$type}.";
            case 'updated':
                return "{$userName} updated a {$type}.";
            case 'deleted':
                return "{$userName} archived a {$type}.";
            default:
                return "{$userName} {$log->event}ed a {$type}.";
        }
    }
}
