<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'membership';
    protected $primaryKey = 'member_ID';

    protected $fillable = [
        'member_code',
        'member_type',
        'user_ID',
    ];
}