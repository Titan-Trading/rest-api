<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\DiscountCode;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    /**
     * List discount codes
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = DiscountCode::query();

        // search by discount type
        if($request->has('type') && $request->type) {
            $type = $request->type;

            $query->whereHas('type', function($q) use ($type) {
                $q->whereName($type);
            });
        }

        // search by user that created the discount code
        if($request->has('creator_id') && $request->creator_id) {
            $query->whereCreatorId($request->creator_id);
        }

        $discountCodes = $query->get();

        return response()->json($discountCodes);
    }

    /**
     * Create a new discount code
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'creator_id' => ['required', 'exists:users,id'],
            'type_id' => ['required', 'exists:discount_types,id'],
            'code' => ['required', 'unique:discount_codes,code'],
            'max_uses' => ['integer', 'min:1'],
            'discount_amount' => ['required', 'decimal'],
            'order_minimum_required' => ['decimal'],
        ], [
            'creator_id_required' => 'Creator id is required',
            'creator_id_exists' => 'Creator must exist',
            'type_id_required' => 'Type id is required',
            'type_id_exists' => 'Discount type must exist',
            'code_required' => 'Discount code is required',
            'code_unique' => 'Discount code must be unique',
            'max_uses_integer' => 'Max uses must be an integer',
            'max_uses_min' => 'Max uses must be greater than or equal to one',
            'discount_amount_required' => 'Discount amount is required',
            'discount_amount_decimal' => 'Discount amount must a decimal value',
            'order_minimum_required_decimal' => 'Order minimum required amount must a decimal value'
        ]);

        $discountCode = new DiscountCode();
        $discountCode->creator_id = $request->creator_id;
        $discountCode->type_id = $request->type_id;
        $discountCode->code = $request->code;
        $discountCode->max_uses = $request->max_uses;
        $discountCode->uses = 0;
        $discountCode->order_minimum_required = $request->order_minimum_required ? $request->order_minimum_required : null;
        $discountCode->status = $request->status ? $request->status : 'active';
        $discountCode->discount_amount = $request->discount_amount;
        $discountCode->save();

        return response()->json($discountCode, 201);
    }

    /**
     * Update a discount code by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $discountCode = DiscountCode::find($id);
        if(!$discountCode) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // only apply unique rule when code has changed
        $codeRules = ['required'];
        if($discountCode->code != $request->code) {
            $codeRules[] = 'unique:discount_codes,code';
        }

        $this->validate($request, [
            'creator_id' => ['required', 'exists:users,id'],
            'type_id' => ['required', 'exists:discount_types,id'],
            'code' => $codeRules,
            'max_uses' => ['integer', 'min:1'],
            'discount_amount' => ['required', 'decimal'],
            'order_minimum_required' => ['decimal'],
        ], [
            'creator_id_required' => 'Creator id is required',
            'creator_id_exists' => 'Creator must exist',
            'type_id_required' => 'Type id is required',
            'type_id_exists' => 'Discount type must exist',
            'code_required' => 'Discount code is required',
            'code_unique' => 'Discount code must be unique',
            'max_uses_integer' => 'Max uses must be an integer',
            'max_uses_min' => 'Max uses must be greater than or equal to one',
            'discount_amount_required' => 'Discount amount is required',
            'discount_amount_decimal' => 'Discount amount must a decimal value',
            'order_minimum_required_decimal' => 'Order minimum required amount must a decimal value'
        ]);

        $discountCode->creator_id = $request->creator_id;
        $discountCode->type_id = $request->type_id;
        $discountCode->code = $request->code;
        $discountCode->max_uses = $request->max_uses;
        $discountCode->order_minimum_required = $request->order_minimum_required ? $request->order_minimum_required : $discountCode->order_minimum_required;
        $discountCode->status = $request->status ? $request->status : $discountCode->status;
        $discountCode->discount_amount = $request->discount_amount;
        $discountCode->save();

        return response()->json($discountCode);
    }

    /**
     * Delete a discount code by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $discountCode = DiscountCode::find($id);
        if(!$discountCode) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $discountCode->delete();

        return response()->json($discountCode);
    }
}