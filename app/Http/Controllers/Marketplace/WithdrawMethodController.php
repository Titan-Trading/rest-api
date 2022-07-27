<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\SellerAccount;
use App\Models\Marketplace\WithdrawMethod;
use Illuminate\Http\Request;

class SellerAccountWithdrawMethodController extends Controller
{
    /**
     * Get list of seller account withdraw methods
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = WithdrawMethod::query();

        $withdrawMethods = $query->get();

        return response()->json($withdrawMethods);
    }

    /**
     * Add a new withdraw method to a seller account
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'seller_id' => ['required', 'exists:seller_accounts,id'],
            'payment_processor_type_id' => 'required', 'exists:payment_processor_types,id',
            'payment_processor_id' => 'required', 'exists:payment_processors,id',
            'payment_processor_account_id' => 'required'
        ], [
            'seller_id_required' => 'Seller id is required',
            'seller_id_exists' => 'Seller account must exist',
            'payment_processor_type_id_required' => 'Payment processor type id is required',
            'payment_processor_type_id_exists' => 'Payment processor type must exist',
            'payment_processor_id_required' => 'Payment processor id is required',
            'payment_processor_id_exists' => 'Payment processor must exist',
            'payment_processor_account_id_required' => 'Payment processor account is required'
        ]);

        // seller account is owned by current user
        $seller = SellerAccount::find($request->seller_id);
        if($seller->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to add withdraw methods to this seller account'
            ], 403);
        }

        $withdrawMethod = new WithdrawMethod();
        $withdrawMethod->seller_id = $request->seller_id;
        $withdrawMethod->payment_processor_type_id = $request->payment_processor_type_id;
        $withdrawMethod->payment_processor_id = $request->payment_processor_id;
        $withdrawMethod->payment_processor_account_id = $request->payment_processor_account_id;
        $withdrawMethod->save();

        return response()->json($withdrawMethod, 201);
    }

    /**
     * Remove withdraw method from seller account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $withdraw = WithdrawMethod::find($id);
        if(!$withdraw) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if seller account is owned by current user
        if($withdraw->seller()->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to remove withdraw methods from this seller account'
            ], 403);
        }

        $withdraw->delete();

        return response()->json($withdraw, 200);
    }
}