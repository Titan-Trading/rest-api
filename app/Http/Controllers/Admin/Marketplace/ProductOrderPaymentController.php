<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PaymentMethod;
use App\Models\Marketplace\ProductOrder;
use App\Models\Marketplace\ProductOrderPayment;
use Illuminate\Http\Request;

class ProductOrderPaymentController extends Controller
{
    /**
     * Get list of product order payments
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ProductOrderPayment::query();

        $orderPayments = $query->get();

        return response()->json($orderPayments);
    }

    /**
     * Create a manual order payment
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_id' => ['required', 'exists:product_orders,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id']
        ], [
            'order_id_required' => 'Order id is required',
            'order_id_exists' => 'Order must exist',
            'payment_method_id_required' => 'Payment method id is required',
            'payment_method_id_exists' => 'Payment method must exist'
        ]);

        // check if the order is for the current user
        $order = ProductOrder::find($request->order_id);

        // check if the payment method is for the current user
        $paymentMethod = PaymentMethod::find($request->payment_method_id);

        // TODO: apply discount code if one is found and has not already been applied to a previous payment

        // TODO: use payment processor API to actually make the payment

        $orderPayment = new ProductOrderPayment();
        $orderPayment->order_id = $request->order_id;
        $orderPayment->buyer_id = $request->user()->id;
        $orderPayment->payment_method_id = $request->payment_method_id;
        $orderPayment->status = 'completed';
        $orderPayment->commission_amount = 0.00;
        $orderPayment->tax_amount = 0.00;
        $orderPayment->paid_amount = 0.00;
        $orderPayment->save();

        return response()->json($orderPayment, 201);
    }
}