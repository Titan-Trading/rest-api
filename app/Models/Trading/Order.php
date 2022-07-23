<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'connected_exchange_id',
        'order_id', // exchange order id
        'tradeable_id', // id of the record that initiated the trade
        'tradeable_type', // type of the record that initiated the trade
        'status',
        'is_test',
        'side',
        'type',
        'fill_type',
        'quantity',
        'price',
        'base_symbol',
        'target_symbol',
        'added_to_exchange_at',
        'fill_started_at',
        'fill_completed_at'
    ];

    /**
     * User account for the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Connected exchange account that the order was made under
     */
    public function connectedExchange()
    {
        return $this->belongsTo(ConnectedExchange::class);
    }

    /**
     * Fills that took place on the exchange
     */
    public function fills()
    {
        return $this->hasMany(OrderFill::class, 'id', 'order_id');
    }
}
