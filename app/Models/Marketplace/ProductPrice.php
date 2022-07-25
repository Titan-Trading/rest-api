<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_prices';

    protected $fillable = [
        'type_id',
        'product_id',
        'is_active',
        'name',
        'price_value'
    ];

    /**
     * The type of price for the product
     *
     * @return void
     */
    public function type()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    /**
     * The product the price is for
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
