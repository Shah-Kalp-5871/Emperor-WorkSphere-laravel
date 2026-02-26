<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'encrypted_employee_id',
        'message',
        'is_admin_reply',
        'admin_id',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(AnonymousChatSession::class, 'session_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
