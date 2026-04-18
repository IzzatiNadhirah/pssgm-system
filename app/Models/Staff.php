<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    // 1. Define the exact table name
    protected $table = 'staff';

    // 2. Define the custom primary key
    protected $primaryKey = 'staff_ID';

    // 3. Authorize columns for data entry
    protected $fillable = [
        'staff_code',
        'name',
        'email',
        'password',
    ];
}