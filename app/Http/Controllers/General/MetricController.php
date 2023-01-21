<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Metric;
use Illuminate\Http\Request;

class MetricController extends Controller
{
    public function index(Request $request, $name)
    {
        $metrics = Metric::whereName($name)
            ->where('metricable_type', 'user')
            ->where('metricable_id', $request->user()->id)
            ->first();

        if(!$metrics) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        
        return response()->json(json_decode($metrics->value));
    }
}