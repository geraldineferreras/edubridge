<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // ✅ Add this
use App\Models\Mentor;

class Schedule extends Model
{
    protected $fillable = ['mentor_id', 'date', 'time', 'course'];

    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}
