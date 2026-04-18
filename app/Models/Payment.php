<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_ID';

    protected $fillable = [
        'payment_code',
        'amount',
        'payment_date',
        'payment_status',
        'receipt_path',
        'member_ID',
    ];
}