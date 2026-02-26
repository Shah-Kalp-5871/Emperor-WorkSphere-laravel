<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DailyLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\DailyLogResource;

class DailyLogController extends Controller
{
    public function __construct(
        protected DailyLogService $dailyLogService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'date', 'employee_id']);
        $logs = $this->dailyLogService->listLogs($filters);
        return DailyLogResource::collection($logs);
    }

    public function show(int $id)
    {
        $log = $this->dailyLogService->getLogById($id);
        return new DailyLogResource($log);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:submitted,reviewed,revision_requested',
            'admin_notes' => 'nullable|string',
        ]);

        $log = $this->dailyLogService->reviewLog($id, $validated);
        return response()->json(['message' => 'Daily log reviewed successfully', 'data' => new DailyLogResource($log)]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->dailyLogService->deleteLog($id);
        return response()->json(['message' => 'Daily log deleted successfully']);
    }
}
