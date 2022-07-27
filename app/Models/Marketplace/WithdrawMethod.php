<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_account_withdraw_methods';

    protected $fillable = [
        'seller_id',
        'payment_processor_type_id',
        'payment_processor_id',
        'payment_processor_account_id',
        'metadata'
    ];

    /**
     * Seller account making the withdraw
     *
     * @return void
     */
    public function seller()
    {
        return $this->belongsTo(SellerAccount::class, 'seller_id');
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