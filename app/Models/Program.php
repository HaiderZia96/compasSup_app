<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Abbasudo\Purity\Traits\Filterable;

class Program extends Model
{
//    use Filterable;
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
        'keyword_option_one',
        'keyword_secondary_one',
        'keyword_main_one',
        'is_fav',
        'created_by',
        'updated_by',
    ];

    public function getIsFavAttribute($value)
    {
        return $value == 1 ? true : false;
    }
    // Method to increment view count
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    // Method to increment apply count
    public function incrementApplyCount()
    {
        $this->increment('apply_count');
    }
}
