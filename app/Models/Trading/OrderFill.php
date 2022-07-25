<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_fills';

    protected $fillable = [
        'order_id',
        'quantity',
        'price',
        'filled_at'
    ];

    /**
     * Order the fill belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
