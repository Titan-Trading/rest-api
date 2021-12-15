<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'currencies';

    protected $fillable = [
        'type_id',
        'name'
    ];

    /**
     * Type of currency
     */
    public function type()
    {
        return $this->belongsTo(CurrencyType::class, 'type_id');
    }
}