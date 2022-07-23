<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $table = 'indicators';

    protected $fillable = [
        'name',
        'is_active',
        'algorithm_text'
    ];

    /**
     * Metadata pertaining to the market indicator
     */
    public function metadata()
    {
        return $this->hasMany(IndicatorMetadata::class, 'indicator_id');
    }
}