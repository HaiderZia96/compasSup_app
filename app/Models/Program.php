<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'formation_id',
        'name_of_the_formation',
        'link_to_its_webpage',
        'region',
        'schooling_cost',
        'length_of_the_formation',
        'status',
        'access_rate',
        'type_of_formation',
        'town',
        'schooling_modalities',
        'schooling_pursuit',
        'description_of_the_formation',
        'number_of_students',
        'keywords_option',
        'keywords_secondary',
        'keywords_main',
        'created_by',
        'updated_by',
    ];
}
