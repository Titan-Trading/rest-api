<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exchange_accounts';

    protected $fillable = [
        'user_id',
        'exchange_id',
        'api_key',
        'api_key_secret',
        'wallet_private_key',
    ];

    protected $hidden = [
        'api_key_secret'
    ];

    /**
     * User account that's connected to the exchange
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Exchange
     */
    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}
