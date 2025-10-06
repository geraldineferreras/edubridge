<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mentee extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'age',
        'location',
        'profile_picture',
        'gender',
        'skills',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'mentee_id');
    }

    public function sessions()
{
    return $this->hasMany(GroupSessionParticipant::class, 'mentee_id', 'id');
}

public function joinedSessions()
{
    return $this->belongsToMany(
        GroupSession::class,
        'group_session_participants',
        'mentee_id',
        'session_id'
    );
}


}


