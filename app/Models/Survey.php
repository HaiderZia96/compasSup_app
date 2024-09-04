<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'possible_answer',
        'answer',
        'status',
        'created_by',
        'updated_by'
    ];

}
