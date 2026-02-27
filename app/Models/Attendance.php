<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'punch_in_time',
        'punch_in_latitude',
        'punch_in_longitude',
        'punch_out_time',
        'punch_out_latitude',
        'punch_out_longitude',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'punch_in_latitude' => 'decimal:8',
        'punch_in_longitude' => 'decimal:8',
        'punch_out_latitude' => 'decimal:8',
        'punch_out_longitude' => 'decimal:8',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
