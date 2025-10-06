<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupSessionParticipant extends Model
{
    use HasFactory;

    protected $table = 'group_session_participants';

    protected $fillable = [
        'session_id',
        'mentee_id',
    ];

    public function session()
    {
        return $this->belongsTo(GroupSession::class, 'session_id');
    }

    public function mentee()
    {
        return $this->belongsTo(Mentee::class, 'mentee_id');
    }
}
