<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    /** @use HasFactory<\Database\Factories\DailyLogFactory> */
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'log_date',
        'morning_summary',
        'afternoon_summary',
        'tasks_completed',
        'mood',
        'blockers',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'tasks_completed' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
