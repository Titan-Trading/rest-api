<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeConditionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trade_condition_values';

    protected $fillable = [
        'value_type',
        'value'
    ];
}
