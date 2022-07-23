<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorMetadata extends Model
{
    use HasFactory;

    protected $table = 'indicator_metadata';

    protected $fillable = [
        'indicator_id',
        'key',
        'value'
    ];

    /**
     * The market indicator that pertains to the market indicator metadata
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
