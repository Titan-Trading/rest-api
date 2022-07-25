<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrderPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_order_payments';

    protected $fillable = [
        'order_id',
        'buyer_id',
        'payment_processor_type_id',
        'payment_processor_id',
        'payment_process_account_id',
        'status',
        'commission_amount',
        'tax_amount',
        'paid_amount'
    ];

    /**
     * Order that the payment is for
     *
     * @return void
     */
    public function order()
    {
        return $this->belongsTo(ProductOrder::class, 'order_id');
    }

    /**
     * User that is buying the product in the order
     *
     * @return void
     */
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Payment method used on the order
     *
     * @return void
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentProcessor::class, 'payment_method_type_id');
    }
}
