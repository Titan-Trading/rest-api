<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Get list of user payment methods
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        $paymentMethods = $query->get();

        return response()->json($paymentMethods);
    }

    /**
     * Add a new payment method to a user
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => ['required', 'exists:users,id'],
            'payment_processor_type_id' => 'required', 'exists:payment_processor_types,id',
            'payment_processor_id' => 'required', 'exists:payment_processors,id',
            'payment_processor_account_id' => 'required'
        ], [
            'user_id_required' => 'User id is required',
            'user_id_exists' => 'User account must exist',
            'payment_processor_type_id_required' => 'Payment processor type id is required',
            'payment_processor_type_id_exists' => 'Payment processor type must exist',
            'payment_processor_id_required' => 'Payment processor id is required',
            'payment_processor_id_exists' => 'Payment processor must exist',
            'payment_processor_account_id_required' => 'Payment processor account is required'
        ]);

        // user id matches current user
        if($request->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to add payment methods to this user account'
            ], 403);
        }

        $paymentMethod = new PaymentMethod();
        $paymentMethod->user_id = $request->user_id;
        $paymentMethod->payment_processor_type_id = $request->payment_processor_type_id;
        $paymentMethod->payment_processor_id = $request->payment_processor_id;
        $paymentMethod->payment_processor_account_id = $request->payment_processor_account_id;
        $paymentMethod->save();

        return response()->json($paymentMethod, 201);
    }

    /**
     * Remove payment method from seller account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $payment = PaymentMethod::find($id);
        if(!$payment) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if seller account is owned by current user
        if($payment->seller()->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to remove payment methods from this seller account'
            ], 403);
        }

        $payment->delete();

        return response()->json($payment);
    }
}