<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeCondition extends Model
{
    use HasFactory;

    protected $table = 'trade_conditions';

    protected $fillable = [
        'conditional_trade_id',
        'market_indicator_id',
        'value_id',
        'comparative_operator'
    ];

    /**
     * Conditional trade
     */
    public function conditionalTrade()
    {
        return $this->belongsTo(ConditionalTrade::class);
    }

    /**
     * The market indicator that pertains to the trade condition
     */
    public function marketIndicator()
    {
        return $this->belongsTo(MarketIndicator::class);
    }

    /**
     * Value for the trade condition
     */
    public function value()
    {
        return $this->hasOne(TradeConditionValue::class, 'id', 'value_id');
    }
}