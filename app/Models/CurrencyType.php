<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'currency_types';

    protected $fillable = [
        'name'
    ];

    /**
     * Currencies of a given type
     */
    public function currencies()
    {
        return $this->hasMany(Currency::class, 'type_id');
    }
}
