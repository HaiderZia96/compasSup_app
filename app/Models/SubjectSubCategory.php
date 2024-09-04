<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'created_by',
        'updated_by'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'sub_category_id');
    }

}
