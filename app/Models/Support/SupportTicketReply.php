<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    use HasFactory;

    protected $table = 'support_ticket_replies';

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'message'
    ];

    /**
     * Get the ticket the reply belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    /**
     * Get the staff user the ticket belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}