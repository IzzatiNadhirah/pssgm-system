<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    // KITA EJAS SINI: Tukar jadi huruf kecil session_id & user_id
    protected $fillable = ['session_id', 'user_id', 'date', 'status'];

    public function user() {
        // Foreign key dalam table attendances ialah user_id
        return $this->belongsTo(User::class, 'user_id', 'user_ID');
    }
}