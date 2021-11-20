<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketIndicatorMetadata extends Model
{
    use HasFactory;

    protected $table = 'market_indicator_metadata';

    protected $fillable = [
        'market_indicator_id',
        'key',
        'value'
    ];

    /**
     * The market indicator that pertains to the market indicator metadata
     */
    public function marketIndicator()
    {
        return $this->belongsTo(MarketIndicator::class);
    }
}
