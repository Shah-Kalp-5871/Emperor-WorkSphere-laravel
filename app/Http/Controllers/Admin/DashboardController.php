<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Services\Admin\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * GET /api/admin/dashboard/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard statistics retrieved successfully.',
            'data'    => [
                'stats'           => $this->dashboardService->getStats(),
                'active_projects' => $this->dashboardService->getActiveProjects(5),
                'recent_activity' => $this->dashboardService->getRecentActivity(10),
                'overdue_tasks'   => TaskResource::collection($this->dashboardService->getOverdueTasks(5)),
            ],
        ]);
    }
}
