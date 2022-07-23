<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotSession extends Model
{
    use HasFactory;

    protected $table = 'bot_sessions';

    protected $fillable = [
        'user_id',
        'connected_exchange_id',
        'bot_id',
        'name',
        'parameters',
        'mode',
        'active',
        'started_at',
        'ended_at'
    ];

    /**
     * User account that started the bot session
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Connected exchange that pertains the bot session
     */
    public function connectedExchange()
    {
        return $this->belongsTo(ConnectedExchange::class);
    }

    /**
     * Bot that pertains the bot session
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
