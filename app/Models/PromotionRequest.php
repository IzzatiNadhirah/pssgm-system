<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionRequest extends Model
{
    use HasFactory;

    // Senarai column yang dibenarkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'user_ID', 
        'instructor_ID', 
        'current_bengkung', 
        'requested_bengkung', 
        'status', 
        'remarks'
    ];

    // Hubungan dengan Table Users (Pelajar)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID'); // Ejas user_ID kalau PK table bos nama 'id'
    }

    // Hubungan dengan Table Instructor (Cikgu)
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }
}