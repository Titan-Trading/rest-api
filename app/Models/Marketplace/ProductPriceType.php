<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPriceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_price_types';

    protected $fillable = [
        'name'
    ];
}
