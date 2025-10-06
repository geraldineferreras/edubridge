<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupSession extends Model
{
    use HasFactory;

    protected $fillable = [
    'mentor_id',
    'title',
    'description',
    'start_time',
    'end_time',
    'capacity',
    'status',
    'room_id',
    'meet_link',
];


    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    public function participants()
    {
        // your migration uses session_id as FK
        return $this->hasMany(GroupSessionParticipant::class, 'session_id');
    }

    public function isFull(): bool
    {
        return $this->participants()->count() >= $this->capacity;
    }
}
