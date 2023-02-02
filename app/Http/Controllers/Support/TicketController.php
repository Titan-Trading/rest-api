<?php
namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Get list of support tickets for the current user
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $tickets = SupportTicket::whereUserId($request->user()->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return response()->json($tickets);
    }
}