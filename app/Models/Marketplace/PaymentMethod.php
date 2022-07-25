<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_payment_methods';

    protected $fillable = [
        'user_id',
        'payment_processor_type_id',
        'payment_processor_id',
        'payment_processor_account_id',
        'metadata'
    ];

    /**
     * User account making the payment
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The payment processor type
     *
     * @return void
     */
    public function paymentProcessorType()
    {
        return $this->belongsTo(PaymentProcessorType::class, 'payment_processor_type_id');
    }

    /**
     * The payment processor
     *
     * @return void
     */
    public function paymentProcessor()
    {
        return $this->belongsTo(PaymentProcessor::class, 'payment_processor_id');
    }
}