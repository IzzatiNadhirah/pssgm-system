<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructor';
    protected $primaryKey = 'instructor_ID';

    protected $fillable = [
        'instructor_code',
        'name',
        'address',
        'email',
        'password',
        'tel_number',
    ];
}