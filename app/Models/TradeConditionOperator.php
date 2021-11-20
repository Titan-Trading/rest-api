<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeConditionOperator extends Model
{
    use HasFactory;

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