<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'possible_answer',
        'status',
        'min_answer_count',
        'answer_key',
        'main_question_id',
        'created_by',
        'updated_by'
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
    public function subQuestions()
    {
        return $this->hasMany(SubQuestion::class, 'question_id');
    }
}
