<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cawangan extends Model
{
    use HasFactory;

    protected $table = 'cawangan';
    protected $primaryKey = 'caw_ID';

    protected $fillable = [
        'caw_code',
        'caw_name',
        'caw_address',
        'staff_ID',
    ];
}