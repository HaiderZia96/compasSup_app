<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'status',
        'type',
        'min_answer_count',
        'question_id',
        'created_by',
        'updated_by'
    ];
    public function subAnswers()
    {
        return $this->hasMany(Answer::class, 'sub_question_id');
    }
}
