<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelanggang extends Model
{
    use HasFactory;

    protected $table = 'gelanggang';
    protected $primaryKey = 'gel_ID';

    protected $fillable = [
        'gel_code',
        'gel_name',
        'gel_address',
        'caw_ID',
        'instructor_ID',
    ];
}