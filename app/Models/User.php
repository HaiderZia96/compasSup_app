<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,HasRoles, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'date_of_birth',
        'high_school',
        'country_code',
        'mobile_number',
        'postal_code',
        'image',
        'email',
        'password',
        'user_role',
        'email_verified_at',
        'type_of_baccalaureate',
        'specialities ',
       'european_section',
       'options',
        'filliere_de_formation',
       'general_mean',
       'subject_id',
       'learning_a_language ',
       'language ',
       'international_experience ',
       'traveling_to_a_peculiar_region ',
       'region ',
       'prefer_school ',
       'study ',
       'minimum_monthly_cost ',
       'pay_for_your_studies',
       'professionalizing formation',
       'study_online',
       'iapprentissage'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
