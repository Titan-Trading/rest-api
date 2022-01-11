<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConditionalTrade extends Model
{
    use HasFactory;

    protected $table = 'conditional_trades';

    protected $fillable = [
        'user_id',
        'connected_exchange_id',
        'parent_conditional_trade_id',
        'is_test',
        'is_active',
        'status',
        'side',
        'base_symbol',
        'target_symbol'
    ];

    /**
     * User that the conditional trade belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Connected exchange that pertains the conditional trade
     */
    public function connectedExchange()
    {
        return $this->belongsTo(ConnectedExchange::class);
    }

    /**
     * Parent conditional trade
     */
    public function parentConditionalTrade()
    {
        return $this->belongsTo(ConditionalTrade::class, 'parent_conditional_trade_id', 'id');
    }
}
