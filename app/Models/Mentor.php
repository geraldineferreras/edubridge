<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mentor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mentors';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'age',
        'gender',
        'job_title',
        'company',
        'location',
        'category',
        'skills',
        'bio',
        'website',
        'twitter',
        'years_experience',
        'relevant_skills',
        'industries',
        'mentoring_experience',
        'notable_projects',
        'profile_photo',
        'resume',
    ];

    protected $hidden = [
        'password',
        'remember_token', // added for Laravel auth compatibility
    ];

    // ✅ Relationships
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'mentor_id');
    }
    
   public function sessions()
{
    return $this->hasMany(GroupSession::class, 'mentor_id');
}


    // (we’ll add requests/sessions relationships later in Step 2-3)
}
