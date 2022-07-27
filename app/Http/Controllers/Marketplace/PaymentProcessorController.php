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
        $query = PaymentProcessor::query();

        if($request->has('type')) {
            $type = $request->type;
            $query->whereHas('types', function($q) use ($type) {
                $q->whereName($type);
            });
        }

        $paymentProcessors = $query->get();

        return response()->json($paymentProcessors);
    }
}