<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotSessionResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bot_session_results';

    protected $fillable = [
        'bot_session_id',
        'total_pips',
        'total_trades',
        'total_wins',
        'total_losses',
        'winning_percent',
        'risk_percent_per_position',
        'max_dollar_drawdown',
        'total_dollar_gain',
        'total_dollar_percent_gain',
        'starting_balance',
        'ending_balance'
    ];
}
