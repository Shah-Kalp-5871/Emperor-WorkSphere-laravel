<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * GET /api/employee/dashboard
     */
    public function index(): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData();

        if (isset($data['error'])) {
            return response()->json([
                'success' => false,
                'message' => $data['error']
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee dashboard data retrieved successfully.',
            'data' => $data
        ]);
    }

    /**
     * GET /api/employee/dashboard/stats
     * Fast endpoint designed specifically for the sidebar to show badge counts.
     */
    public function sidebarStats(): JsonResponse
    {
        $user = auth('api')->user();
        if (!$user || !$user->employee) {
            return response()->json(['open_tasks' => 0, 'assigned_projects' => 0]);
        }

        $open_tasks = $user->employee->tasks()->where('status', '!=', 'completed')->count();
        $assigned_projects = $user->employee->projects()->count();

        return response()->json([
            'open_tasks' => $open_tasks,
            'assigned_projects' => $assigned_projects
        ]);
    }
}
