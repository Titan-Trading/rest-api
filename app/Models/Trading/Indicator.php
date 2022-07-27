<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicator extends Model
{
    use HasFactory, SoftDeletes;

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