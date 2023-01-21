<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    use HasFactory;

    protected $table = 'metrics';

    protected $fillable = [
        'name',
        'metricable_id',
        'metricable_type',
        'value'
    ];

    /**
     * Get the model that the metric belongs to.
     */
    public function metricable()
    {
        return $this->morphTo(__FUNCTION__, 'metricable_type', 'metricable_id');
    }
}