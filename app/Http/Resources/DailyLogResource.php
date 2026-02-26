<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee->user->name ?? 'N/A',
            'log_date' => $this->log_date?->format('Y-m-d'),
            'morning_summary' => $this->morning_summary,
            'afternoon_summary' => $this->afternoon_summary,
            'tasks_completed' => $this->tasks_completed,
            'mood' => $this->mood,
            'blockers' => $this->blockers,
            'status' => $this->status,
            'admin_notes' => $this->admin_notes,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_name' => $this->reviewer->name ?? null,
            'reviewed_at' => $this->reviewed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
