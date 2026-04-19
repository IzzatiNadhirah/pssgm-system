<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// 1. Add this line to import the Authenticatable class
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

// 2. Change 'extends Model' to 'extends Authenticatable'
class Instructor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'instructor';
    protected $primaryKey = 'instructor_ID';

    // Added this to ensure Laravel handles your custom ID correctly
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'instructor_code',
        'name',
        'address',
        'email',
        'password',
        'tel_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}