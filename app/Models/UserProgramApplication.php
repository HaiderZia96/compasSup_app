<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgramApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'program_id',
    ];
    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('F j, Y, D g:i A');
    }
}
