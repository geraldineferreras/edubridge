<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = ['email', 'code', 'created_at'];
    public $timestamps = false; // We only use created_at manually
}
