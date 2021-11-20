<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fill extends Model
{
    use HasFactory;

    protected $table = 'fills';

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
