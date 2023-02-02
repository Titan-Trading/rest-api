<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'staff_user_id',
        'status',
        'priority',
        'subject',
        'message'
    ];

    /**
     * Get the user the ticket belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the staff user the ticket belongs to.
     */
    public function staffUser()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }
}