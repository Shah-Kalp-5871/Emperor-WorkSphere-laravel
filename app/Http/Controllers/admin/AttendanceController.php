<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('employee.user')
            ->orderByDesc('date')
            ->orderByDesc('punch_in_time');

        // Filter by date
        if ($request->date) {
            $query->where('date', $request->date);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by employee name
        if ($request->search) {
            $query->whereHas('employee.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $records = $query->get()->map(function ($a) {
            return [
                'id'                  => $a->id,
                'employee_name'       => $a->employee->user->name ?? 'N/A',
                'employee_code'       => $a->employee->employee_code ?? 'N/A',
                'date'                => $a->date->format('Y-m-d'),
                'punch_in_time'       => $a->punch_in_time,
                'punch_out_time'      => $a->punch_out_time ?? '—',
                'status'              => $a->status,
                'punch_in_latitude'   => $a->punch_in_latitude,
                'punch_in_longitude'  => $a->punch_in_longitude,
                'punch_out_latitude'  => $a->punch_out_latitude,
                'punch_out_longitude' => $a->punch_out_longitude,
                'working_hours'       => $this->calcHours($a->punch_in_time, $a->punch_out_time),
            ];
        });

        return response()->json(['status' => 'success', 'data' => $records]);
    }

    private function calcHours($in, $out)
    {
        if (!$in || !$out) return '—';
        try {
            $start = \Carbon\Carbon::parse($in);
            $end   = \Carbon\Carbon::parse($out);
            $mins  = $start->diffInMinutes($end);
            return floor($mins / 60) . 'h ' . ($mins % 60) . 'm';
        } catch (\Exception $e) {
            return '—';
        }
    }
}
