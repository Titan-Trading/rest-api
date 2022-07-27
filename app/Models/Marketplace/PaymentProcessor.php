<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProcessor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_processors';

    protected $fillable = [
        'slug',
        'name'
    ];

    /**
     * Types the payment processor supports
     *
     * @return void
     */
    public function types()
    {
        return $this->belongsToMany(PaymentProcessorType::class);
    }
}
