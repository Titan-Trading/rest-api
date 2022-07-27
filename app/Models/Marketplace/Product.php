<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'seller_id',
        'sellable_type',
        'sellable_id',
        'featured_image_id',
        'is_featured',
        'status',
        'name',
        'description',
        'rating',
        'quantity',
        'sold'
    ];

    /**
     * User that created and is selling the product
     *
     * @return void
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * The sellable item being sold
     *
     * @return void
     */
    public function sellable()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    /**
     * The main/featured image of the product
     *
     * @return void
     */
    public function featuredImage()
    {
        return $this->hasOne(Image::class, 'featured_image_id');
    }

    /**
     * The pricing models for the product
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'id', 'product_id');
    }
}
