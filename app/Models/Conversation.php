<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['mentee_user_id','mentor_id','last_message_at'];

    public function mentee()
    {
        return $this->belongsTo(\App\Models\User::class, 'mentee_user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(\App\Models\Mentor::class, 'mentor_id');
    }

    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(\App\Models\Message::class)->latestOfMany();
    }
}
