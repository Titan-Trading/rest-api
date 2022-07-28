<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\DiscountCode;
use App\Models\Marketplace\Product;
use App\Models\Marketplace\ProductOrder;
use App\Models\Marketplace\ProductPrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    /**
     * Get list of product orders
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ProductOrder::query();

        $orders = $query->get();

        return response()->json($orders);
    }

    /**
     * Create a product order
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_price_id' => ['required', 'exists:product_prices,id'],
            'discount_code' => ['exists:discount_codes,name']
        ], [
            'product_price_id_required' => 'Product price id is required',
            'product_price_id_exists' => 'Product price must exist',
            'discount_code_exists' => 'Discount code must exist'
        ]);

        // check if buyer has a payment method
        $userWithPaymentMethod = User::whereId($request->user()->id)->has('paymentMethods')->count();
        if(!$userWithPaymentMethod) {
            return response()->json([
                'message' => 'User has not setup a payment method'
            ], 422);
        }

        $discountCode = DiscountCode::whereCode($request->discount_code)->first();

        // check if discount code criteria is met
        $orderTotal = 0.00;
        if($discountCode->order_minimum_required && $orderTotal <= $discountCode->order_minimum_required) {
            return response()->json([
                'message' => 'Order does not meet the minimum price requirements'
            ], 422);
        }
        else if($discountCode->uses+1 > $discountCode->max_uses) {
            return response()->json([
                'message' => 'Discount code exceeds max uses'
            ], 422);
        }

        // get product using product price id
        $productPriceId = $request->product_price_id;
        $product = Product::whereHas('prices', function($q) use ($productPriceId) {
            $q->whereId($productPriceId);
        })->get();

        // check if product quantity is null or 0
        if(!$product->quantity) {
            return response()->json([
                'message' => 'Product is sold out'
            ], 422);
        }

        // check if product can be sold
        if($product->status == 'pending' || $product->status == 'denied') {
            return response()->json([
                'message' => 'Product cannot be sold'
            ], 422);
        }

        $productOrder = new ProductOrder();
        $productOrder->seller_id = $product->seller_id;
        $productOrder->buyer_id = $request->user()->id;
        $productOrder->product_id = $product->id;
        $productOrder->product_price_id = $productPriceId;
        $productOrder->discount_code_id = $discountCode->id;
        $productOrder->status = 'pending';
        $productOrder->next_payment_at = Carbon::now();
        $productOrder->save();

        return response()->json($productOrder, 201);
    }

    /**
     * Get a product order by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $productOrder = ProductOrder::find($id);
        if(!$productOrder) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($productOrder);
    }

    /**
     * Delete a product order by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $productOrder = ProductOrder::find($id);
        if(!$productOrder) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $productOrder->delete();

        return response()->json($productOrder);
    }
}