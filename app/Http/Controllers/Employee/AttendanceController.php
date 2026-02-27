<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\AttendanceService;
use Illuminate\Http\Request;
use Exception;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function punchIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            // In a real app, we'd get employee_id from auth()->user()->employee->id
            // For now, let's assume it's passed or derived from auth
            $employeeId = $request->user()->employee->id;
            
            $attendance = $this->attendanceService->punchIn(
                $employeeId,
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Punched in successfully.',
                'data' => $attendance
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function punchOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $employeeId = $request->user()->employee->id;
            
            $attendance = $this->attendanceService->punchOut(
                $employeeId,
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Punched out successfully.',
                'data' => $attendance
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function status(Request $request)
    {
        try {
            $employeeId = $request->user()->employee->id;
            $status = $this->attendanceService->getTodayStatus($employeeId);

            return response()->json([
                'status' => 'success',
                'attendance_status' => $status
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
