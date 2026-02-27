<?php

namespace App\Services\Employee;

use App\Models\Attendance;
use Carbon\Carbon;
use Exception;

class AttendanceService
{
    /**
     * Calculate the distance between two points in meters using Haversine formula.
     */
    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Punch In for an employee.
     */
    public function punchIn($employeeId, $latitude, $longitude)
    {
        $today = Carbon::today()->toDateString();

        // 1. Check if already punched in for today
        $existing = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        if ($existing) {
            throw new Exception('Already punched in for today.');
        }

        // 2. Get Office Settings from config/office.php
        $officeLat    = config('office.latitude');
        $officeLon    = config('office.longitude');
        $officeRadius = config('office.radius');

        if (!$officeLat || !$officeLon) {
            throw new Exception('Office location not configured. Please set it in config/office.php.');
        }

        // 3. Calculate Distance
        $distance = $this->calculateDistance($latitude, $longitude, $officeLat, $officeLon);

        // 4. Validate Radius
        if ($distance > $officeRadius) {
            throw new Exception('Outside office boundary. Your distance: ' . round($distance) . ' meters (allowed: ' . $officeRadius . 'm).');
        }

        // 5. Create Record
        return Attendance::create([
            'employee_id'        => $employeeId,
            'date'               => $today,
            'punch_in_time'      => Carbon::now()->toTimeString(),
            'punch_in_latitude'  => $latitude,
            'punch_in_longitude' => $longitude,
            'status'             => 'PUNCHED_IN',
        ]);
    }

    /**
     * Punch Out for an employee.
     */
    public function punchOut($employeeId, $latitude, $longitude)
    {
        $today = Carbon::today()->toDateString();

        // 1. Check if Punch In exists
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            throw new Exception('No punch-in record found for today.');
        }

        // 2. Check if already punched out
        if ($attendance->status === 'PUNCHED_OUT') {
            throw new Exception('Already punched out for today.');
        }

        // 3. Update Record
        $attendance->update([
            'punch_out_time'      => Carbon::now()->toTimeString(),
            'punch_out_latitude'  => $latitude,
            'punch_out_longitude' => $longitude,
            'status'              => 'PUNCHED_OUT',
        ]);

        return $attendance;
    }

    /**
     * Get today's attendance status for an employee.
     */
    public function getTodayStatus($employeeId)
    {
        $today = Carbon::today()->toDateString();
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', $today)
            ->first();

        return $attendance ? $attendance->status : 'NOT_PUNCHED';
    }
}
