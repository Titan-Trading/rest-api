<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProcessorType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_processor_types';

    protected $fillable = [
        'name'
    ];

    /**
     * Payment processors for a given type
     *
     * @return void
     */
    public function paymentProcessors()
    {
        return $this->hasMany(PaymentProcessor::class);
    }
}
