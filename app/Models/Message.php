<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id','sender_type','sender_id','body','read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class);
    }

    public function isFromMentee(): bool
    {
        return $this->sender_type === 'mentee';
    }

    public function isFromMentor(): bool
{
    return $this->sender_type === 'mentor';
}

}
