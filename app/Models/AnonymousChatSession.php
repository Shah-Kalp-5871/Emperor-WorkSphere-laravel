<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'anonymous_alias',
        'last_seen_at',
        'expires_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(AnonymousChatMessage::class, 'session_id');
    }
}
