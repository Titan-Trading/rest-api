<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Symbol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'symbols';

    protected $fillable = [
        'base_currency_id',
        'target_currency_id'
    ];

    /**
     * Base currency of the symbol
     */
    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }

    /**
     * Target currency of the symbol
     */
    public function targetCurrency()
    {
        return $this->belongsTo(Currency::class, 'target_currency_id');
    }

    /**
     * Exchanges for the currency pair
     */
    public function exchanges()
    {
        return $this->belongsToMany(Exchange::class)->withPivot(['is_active']);
    }

    /**
     * Metadata for a currency pair (settings, etc.)
     */
    public function metadata()
    {
        
    }
}
