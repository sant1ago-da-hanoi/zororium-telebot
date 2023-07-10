<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'update_id',
        'message_id',
        'from_id',
        'from_is_bot',
        'from_username',
        'from_language_code',
        'chat_id',
        'chat_username',
        'chat_type',
        'date',
        'text',
        'is_command',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];
}
