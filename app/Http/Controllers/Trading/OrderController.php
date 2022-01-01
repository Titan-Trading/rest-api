<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Fill;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * List orders
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $orders = Order::query()->get();

        return response()->json($orders, 200);
    }

    /**
     * Create an order record
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'connected_exchange_id' => 'required|exists:connected_exchanges,id',
            'tradeable_id' => 'required',
            'tradeable_type' => 'required',
            'order_id' => 'required',
            'status' => 'required',
            'side' => 'required',
            'type' => 'required',
            'fill_type' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'base_symbol' => 'required',
            'target_symbol' => 'required'
        ], [
            'connected_exchange_id_required' => 'Connected exchange id is required',
            'connected_exchange_id_exists' => 'Connected exchange is not found',
            'tradeable_id_required' => 'Tradeable id is required',
            'tradeable_type_required' => 'Tradeable type is required',
            'order_id_required' => 'Order id is required',
            'status_required' => 'Status is required',
            'side_required' => 'Side is required',
            'type_required' => 'Type is required',
            'fill_type_required' => 'Fill type is required',
            'quantity_required' => 'Quantity is required',
            'price_required' => 'Price is required',
            'base_symbol_required' => 'Base symbol is required',
            'target_symbol_required' => 'Target symbol is required'
        ]);

        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->connected_exchange_id = $request->connected_exchange_id;
        $order->tradeable_id = $request->tradeable_id;
        $order->tradeable_type = $request->tradeable_type;
        $order->order_id = $request->order_id;
        $order->status = $request->status;
        $order->side = $request->side;
        $order->type = $request->type;
        $order->fill_type = $request->fill_type;
        $order->quantity = $request->quantity;
        $order->price = $request->price;
        $order->base_symbol = $request->base_symbol;
        $order->target_symbol = $request->target_symbol;
        $order->is_test = $request->has('is_test') ? $request->is_test : true;
        $order->added_to_exchange_at = $request->has('added_to_exchange_at') ? $request->added_to_exchange_at : null;
        $order->fill_started_at = $request->has('fill_started_at') ? $request->fill_started_at : null;
        $order->fill_completed_at = $request->has('fill_completed_at') ? $request->fill_completed_at : null;
        $order->save();

        return response()->json($order, 201);
    }

    /**
     * Get an order by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($id);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json($order, 200);
    }

    /**
     * Update an order by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($id);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $this->validate($request, [
            'connected_exchange_id' => 'required|exists:connected_exchanges,id',
            'tradeable_id' => 'required',
            'tradeable_type' => 'required',
            'order_id' => 'required',
            'status' => 'required',
            'side' => 'required',
            'type' => 'required',
            'fill_type' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'base_symbol' => 'required',
            'target_symbol' => 'required'
        ], [
            'connected_exchange_id_required' => 'Connected exchange id is required',
            'connected_exchange_id_exists' => 'Connected exchange is not found',
            'tradeable_id_required' => 'Tradeable id is required',
            'tradeable_type_required' => 'Tradeable type is required',
            'order_id_required' => 'Order id is required',
            'status_required' => 'Status is required',
            'side_required' => 'Side is required',
            'type_required' => 'Type is required',
            'fill_type_required' => 'Fill type is required',
            'quantity_required' => 'Quantity is required',
            'price_required' => 'Price is required',
            'base_symbol_required' => 'Base symbol is required',
            'target_symbol_required' => 'Target symbol is required'
        ]);

        $order->connected_exchange_id = $request->connected_exchange_id;
        $order->tradeable_id = $request->tradeable_id;
        $order->tradeable_type = $request->tradeable_type;
        $order->order_id = $request->order_id;
        $order->status = $request->status;
        $order->side = $request->side;
        $order->type = $request->type;
        $order->fill_type = $request->fill_type;
        $order->quantity = $request->quantity;
        $order->price = $request->price;
        $order->base_symbol = $request->base_symbol;
        $order->target_symbol = $request->target_symbol;
        $order->is_test = $request->has('is_test') ? $request->is_test : true;
        $order->added_to_exchange_at = $request->has('added_to_exchange_at') ? $request->added_to_exchange_at : null;
        $order->fill_started_at = $request->has('fill_started_at') ? $request->fill_started_at : null;
        $order->fill_completed_at = $request->has('fill_completed_at') ? $request->fill_completed_at : null;
        $order->save();

        return response()->json($order, 200);
    }

    /**
     * Delete an order by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($id);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response('Success', 200);
    }

    /**
     * Get fills for an order by order id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function getFills(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($id);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $fills = Fill::whereOrderId($id)->get();

        return response()->json($fills, 200);
    }

    /**
     * Store a new fill for an order by order id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function storeFill(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($id);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $this->validate($request, [
            'quantity' => 'required',
            'price' => 'required',
            'filled_at' => 'required'
        ], [
            'quantity_required' => 'Quantity is required',
            'price_required' => 'Price is required',
            'filled_at_required' => 'Filled at timestamp is required'
        ]);

        $fill = new Fill();
        $fill->order_id = $order->id;
        $fill->quantity = $request->quantity;
        $fill->price = $request->price;
        $fill->filled_at = $request->filled_at;
        $fill->save();

        return response()->json($fill, 201);
    }

    /**
     * Delete a fill for an order by order id
     *
     * @param Request $request
     * @param integer $orderId
     * @param integer $id
     * @return void
     */
    public function deleteFill(Request $request, $orderId, $id)
    {
        if(!$orderId) {
            return response()->json([
                'message' => 'Order id is required'
            ], 404);
        }

        $order = Order::find($orderId);
        if(!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        $fill = Fill::find($id);
        if(!$fill) {
            return response()->json([
                'message' => 'Order fill not found'
            ]);
        }

        $fill->delete();

        return response('Success', 200);
    }
}