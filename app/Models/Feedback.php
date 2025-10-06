<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks'; // Make sure this matches your DB table

    protected $fillable = [
        'mentor_id',
        'author',
        'comment',
        'rating',
    ];

    // Feedback belongs to a mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}
