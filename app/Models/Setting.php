<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'office_latitude',
        'office_longitude',
        'office_radius',
    ];

    protected $casts = [
        'office_latitude' => 'decimal:8',
        'office_longitude' => 'decimal:8',
        'office_radius' => 'integer',
    ];
}
