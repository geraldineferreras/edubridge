<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['mentee_id', 'mentor_id', 'status'];

    // Relationships
    public function mentee() {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function mentor() {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }

    public function applications()
{
    return $this->hasMany(Application::class, 'mentee_id');
}

}