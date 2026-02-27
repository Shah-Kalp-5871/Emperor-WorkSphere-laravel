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
}
