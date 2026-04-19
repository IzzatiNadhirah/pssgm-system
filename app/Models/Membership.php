<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'membership';
    protected $primaryKey = 'member_ID';

    // Since your primary key is not 'id', tell Laravel it's an incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'member_code',
        'member_type',
        'user_ID',
    ];

    /**
     * Relationship: A membership belongs to a User.
     */
    public function user()
    {
        // This links user_ID in membership table to user_ID in users table
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }

    /**
     * Relationship: A membership has many Payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'member_ID', 'member_ID');
    }
}