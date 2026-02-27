<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\DailyLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function __construct(
        protected DailyLogService $dailyLogService
    ) {}

    /**
     * GET /api/employee/daily-logs
     */
    public function index(): JsonResponse
    {
        $logs = $this->dailyLogService->getMyLogs();
        return response()->json([
            'success' => true,
            'message' => 'Employee daily logs retrieved successfully.',
            'data' => $logs
        ]);
    }

    /**
     * POST /api/employee/daily-logs
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'log_date' => 'required|date',
            'morning_summary' => 'required|string',
            'afternoon_summary' => 'required|string',
            'mood' => 'nullable|string|in:happy,good,neutral,bad,tired'
        ]);

        try {
            $log = $this->dailyLogService->submitMyLog($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Daily log submitted successfully.',
                'data' => $log
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/employee/daily-logs/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $log = $this->dailyLogService->getMyLogById($id);
            return response()->json([
                'success' => true,
                'message' => 'Daily log retrieved successfully.',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * PUT /api/employee/daily-logs/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
         $request->validate([
            'morning_summary' => 'sometimes|required|string',
            'afternoon_summary' => 'sometimes|required|string',
            'mood' => 'sometimes|nullable|string|in:happy,good,neutral,bad,tired'
        ]);

        try {
            $log = $this->dailyLogService->updateMyLog($id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Daily log updated successfully.',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
