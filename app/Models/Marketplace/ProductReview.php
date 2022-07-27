<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_reviews';

    protected $fillable = [
        'reviewer_id',
        'sellable_type',
        'sellable_id',
        'featured_image_id',
        'rating',
        'is_featured',
        'status',
        'reviewer_name',
        'title',
        'text'
    ];

    /**
     * User that left the review
     *
     * @return void
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * The sellable item the review is for
     *
     * @return void
     */
    public function sellable()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    /**
     * The main/featured image of the review
     *
     * @return void
     */
    public function featuredImage()
    {
        return $this->hasOne(Image::class, 'featured_image_id');
    }
}
