<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PaymentProcessor;
use Illuminate\Http\Request;

class PaymentProcessorController extends Controller
{
    /**
     * Get list of payment processors
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $paymentProcessors = PaymentProcessor::query();

        if($request->has('type_id')) {
            $paymentProcessors->whereTypeId($request->type_id);
        }

        $paymentProcessors = $paymentProcessors->get();

        return response()->json($paymentProcessors);
    }
}