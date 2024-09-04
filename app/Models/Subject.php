<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'sub_category_id',
        'subject_category',
        'status',
        'created_by',
        'updated_by'
    ];


    public function subCategory()
    {
        return $this->belongsTo(SubjectSubCategory::class, 'sub_category_id');
    }
}
