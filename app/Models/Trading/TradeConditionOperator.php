<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeConditionOperator extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trade_condition_operator';

    protected $fillable = [
        'operand1_id',
        'operand2_id',
        'precedence',
        'boolean_operator'
    ];

    /**
     * Operand 1 for the operation
     */
    public function operand1()
    {
        return $this->hasOne(TradeCondition::class, 'id', 'operand1_id');
    }

    /**
     * Operand 2 for the operation
     */
    public function operand2()
    {
        return $this->hasOne(TradeCondition::class, 'id', 'operand2_id');
    }
}