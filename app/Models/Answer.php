<?php

namespace App\Models;

use App\Http\Controllers\Api\QuestionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = [
        'option',
        'question_id',
        'status',
        'created_by',
        'updated_by'
    ];
    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
    public function subQuestions()
    {
        return $this->belongsTo(SubQuestion::class, 'sub_question_id');
    }
}
