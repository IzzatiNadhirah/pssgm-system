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
        'receipt_path', 
        'member_ID',
    ];

    // ==========================================================
    // KITA EJAS SINI: Payment berhubung dengan jadual Membership
    // ==========================================================
    public function membership()
    {
        // Hubungkan kolum 'member_ID' (Payment) kepada 'member_ID' (Membership)
        return $this->belongsTo(Membership::class, 'member_ID', 'member_ID');
    }
}