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
        'remarks',
        'mark_asas',       
        'mark_silibus',    
        'mark_disiplin',  
        'total_mark',
        'approved_by' // <--- KITA DAH TAMBAH SINI
    ];

    // Hubungan dengan Table Users (Pelajar)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID'); 
    }

    // Hubungan dengan Table Instructor (Cikgu)
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_ID', 'instructor_ID');
    }

    // KITA EJAS SINI: Hubungan dengan Table Staff (Staf/Admin yang meluluskan)
    public function staff()
    {
        // Parameter 1: Model Staff
        // Parameter 2: Foreign key di jadual promotion_requests (approved_by)
        // Parameter 3: Primary key di jadual staff (staff_ID)
        return $this->belongsTo(Staff::class, 'approved_by', 'staff_ID');
    }
}