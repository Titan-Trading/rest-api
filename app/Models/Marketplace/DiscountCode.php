<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'discount_codes';

    protected $fillable = [
        'creator_id',
        'type_id',
        'code',
        'max_uses',
        'uses',
        'order_minimum_required',
        'status',
        'discount_amount'
    ];

    /**
     * User that created the discount code
     *
     * @return void
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Type of discount
     *
     * @return void
     */
    public function type()
    {
        return $this->belongsTo(DiscountType::class, 'type_id');
    }
}
