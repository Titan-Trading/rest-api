<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\SellerAccount;
use Illuminate\Http\Request;

class SellerAccountController extends Controller
{
    /**
     * Get list of seller accounts
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $sellers = SellerAccount::query();

        $sellers = $sellers->get();

        return response()->json($sellers);
    }

    /**
     * Create new seller account
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'owner_id' => ['required', 'exists:users,id'],
            'default_withdraw_method_id' => ['required', 'exists:seller_account_withdraw_methods,id'],
            'name' => ['required']
        ], [
            'owner_id_required' => 'Owner id is required',
            'owner_id_exists' => 'Owner must exist',
            'default_withdraw_method_id_required' => 'Withdraw method id is required',
            'default_withdraw_method_id_exists' => 'Withdraw method must exist',
            'name_required' => 'Name is required'
        ]);

        // TODO: verify the payment method details

        $seller = new SellerAccount();
        $seller->owner_id = $request->owner_id;
        $seller->payout_method_type_id = $request->payment_method_type_id;
        $seller->payout_method_id = $request->payment_method_id;
        $seller->name = $request->name;
        $seller->status = 'pending';
        $seller->balance = 0.00;
        $seller->commission_generated = 0.00;
        $seller->revenue_generated = 0.00;
        $seller->balance_updated_at = null;
        $seller->save();

        return response()->json($seller);
    }

    /**
     * Get seller account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $seller = SellerAccount::find($id);
        if(!$seller) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if owner id matches current user id
        if($seller->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to view this seller account'
            ]);
        }

        return response()->json($seller);
    }

    /**
     * Update a seller account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $seller = SellerAccount::find($id);
        if(!$seller) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if owner id matches current user id
        if($seller->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to this seller account'
            ]);
        }

        $this->validate($request, [
            'default_withdraw_method_id' => ['required', 'exists:seller_account_withdraw_methods,id'],
            'name' => ['required']
        ], [
            'default_withdraw_method_id_required' => 'Withdraw method id is required',
            'default_withdraw_method_id_exists' => 'Withdraw method must exist',
            'name_required' => 'Name is required'
        ]);

        $seller->default_withdraw_method_id = $request->default_withdraw_method_id;
        $seller->name = $request->name;
        // $seller->status = $request->status ? $request->status : $seller->status; // TODO: create admin controllers
        $seller->save();

        return response()->json($seller);
    }

    /**
     * Delete a seller account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $seller = SellerAccount::find($id);
        if(!$seller) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if owner id matches current user id
        if($seller->owner_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes this seller account'
            ]);
        }

        $seller->delete();

        return response()->json($seller);
    }
}