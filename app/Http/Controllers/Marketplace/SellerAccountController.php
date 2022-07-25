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
            'payment_method_type_id' => ['required', 'exists:payment_processors,id'],
            'payment_method_id' => ['required'],
            'name' => ['required']
        ], [
            'owner_id_required' => 'Owner id is required',
            'owner_id_exists' => 'Owner must exist',
            'payment_method_type_id_required' => 'Payment method type id is required',
            'payment_method_type_id_exists' => 'Payment method type must exist',
            'payment_method_id_required' => 'Payment method id is required',
            'name_required' => 'Name is required'
        ]);

        $seller = new SellerAccount();
        $seller->owner_id = $request->owner_id;
        $seller->payout_method_type_id = $request->payment_method_type_id;
        $seller->payout_method_id = $request->payment_method_id;
        $seller->name = $request->name;
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

        $this->validate($request, [
            'payment_method_type_id' => ['required', 'exists:payment_processors,id'],
            'payment_method_id' => ['required'],
            'name' => ['required']
        ], [
            'payment_method_type_id_required' => 'Payment method type id is required',
            'payment_method_type_id_exists' => 'Payment method type must exist',
            'payment_method_id_required' => 'Payment method id is required',
            'name_required' => 'Name is required'
        ]);

        $seller->payout_method_type_id = $request->payment_method_type_id;
        $seller->payout_method_id = $request->payment_method_id;
        $seller->name = $request->name;
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

        $seller->delete();

        return response()->json($seller);
    }
}