<?php

namespace App\Http\Controllers\Admin\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\SellerAccount;
use App\Models\Marketplace\Withdraw;
use App\Models\Marketplace\WithdrawMethod;
use Illuminate\Http\Request;

class SellerAccountWithdrawController extends Controller
{
    /**
     * Get list of seller account withdraws
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = Withdraw::query();

        // search withdraws from a given seller account
        if($request->has('seller_id') && $request->seller_id) {
            $query->whereSellerId($request->seller_id);
        }

        $withdraws = $query->get();

        return response()->json($withdraws);
    }

    /**
     * Create a new withdraw request from a seller account
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'seller_id' => ['required', 'exists:seller_accounts,id'],
            'withdraw_method_id' => ['required', 'exists:seller_account_withdraw_methods,id'],
            'payout_amount' => ['decimal']
        ], [
            'seller_id_required' => 'Seller id is required',
            'seller_id_exists' => 'Seller account must exist',
            'withdraw_method_id_required' => 'Withdraw method id is required',
            'withdraw_method_id_exists' => 'Withdraw method must exist',
            'payout_amount_decimal' => 'Payout amount must be a decimal'
        ]);

        $seller = SellerAccount::find($request->seller_id);
        $withdrawMethod = WithdrawMethod::find($request->withdraw_method_id);

        // check if withdraw method matches current seller account
        if($withdrawMethod->seller_id !== $request->seller_id) {
            return response()->json([
                'message' => 'Unauthorized to withdraw from this seller account'
            ], 403);
        }

        // check if balance satisfies the amount being withdrawn (with taxes included)
        if($seller->account_balance < $request->payout_amount) {
            return response()->json([
                'message' => 'Insufficient balance to perform the withdraw'
            ], 403);
        }

        $withdraw = new Withdraw();
        $withdraw->seller_id = $request->seller_id;
        $withdraw->withdraw_method_id = $request->withdraw_method_id;
        $withdraw->payout_amount = 0.00;
        $withdraw->commission_amount = 0.00;
        $withdraw->tax_amount = 0.00;
        $withdraw->balance_after_withdraw = 0.00;
        $withdraw->status = 'pending';
        $withdraw->save();

        return response()->json($withdraw, 201);
    }
}