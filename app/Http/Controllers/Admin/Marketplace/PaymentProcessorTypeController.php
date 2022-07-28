<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PaymentProcessorType;
use Illuminate\Http\Request;

class PaymentProcessorTypeController extends Controller
{
    /**
     * Get list of payment processor types
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = PaymentProcessorType::query();

        $paymentProcessorTypes = $query->get();

        return response()->json($paymentProcessorTypes);
    }
}