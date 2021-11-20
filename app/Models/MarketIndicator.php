<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketIndicator extends Model
{
    use HasFactory;

    protected $table = 'market_indicators';

    protected $fillable = [
        'name',
        'is_active'
    ];

    /**
     * Metadata pertaining to the market indicator
     */
    public function metadata()
    {
        return $this->hasMany(MarketIndicatorMetadata::class, 'id', 'market_indicator_id');
    }
}