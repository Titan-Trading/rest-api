<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'exchanges';

    protected $fillable = [
        'logo_id',
        'name',
        'website_url',
        'is_active',
        'is_dex',
        'symbol_template'
    ];

    protected $hidden = [
        'pivot'
    ];

    /**
     * Types of markets supported
     */
    public function supportedMarketTypes()
    {
        return $this->belongsToMany(MarketType::class);
    }

    /**
     * Logo image
     */
    public function logoImage()
    {
        return $this->hasOne(Image::class, 'logo_id');
    }

    /**
     * All currency pairs for the exchange
     */
    public function symbols()
    {
        return $this->belongsToMany(Symbol::class);
    }
}
