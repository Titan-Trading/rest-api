<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicatorTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'indicator_tests';

    protected $fillable = [
        'user_id',
        'exchange_account_id',
        'bot_id',
        'indicator_id',
        'name',
        'bot_parameters',
        'indicator_parameters',
        'active',
        'started_at',
        'ended_at'
    ];

    /**
     * User account that started the indicator test
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Connected exchange that pertains the indicator test
     */
    public function connectedExchange()
    {
        return $this->belongsTo(ExchangeAccount::class);
    }

    /**
     * Bot that pertains the indicator test
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    /**
     * Indicator that pertains the indicator test
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
