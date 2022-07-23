<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'market_types';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'pivot'
    ];

    /**
     * Exchanges for the type
     */
    public function exchanges()
    {
        return $this->belongsToMany(Exchange::class);
    }
}
