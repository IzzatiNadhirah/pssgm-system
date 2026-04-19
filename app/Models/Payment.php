<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'payment_ID';

    // Tell Laravel this custom ID is an auto-incrementing number
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'payment_code',
        'amount',
        'payment_date',
        'payment_status',
        'receipt_path', // Ready for your future QR scan/upload!
        'member_ID',
    ];
}