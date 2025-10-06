<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Session extends Model
{
    use HasFactory;

    protected $table = 'mentee_sessions';

    protected $fillable = [
    'mentee_id',
    'mentor_id',
    'subject',
    'start_time',
    'end_time',
    'session_date',
];


    // Relationships
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(Mentee::class, 'mentee_id');
    }

    protected static function booted()
{
    static::creating(function ($session) {
        if (empty($session->room_id)) {
            $session->room_id = 'room_'.Str::random(12);
        }
    });
}
}
