<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_account_withdraws';

    protected $fillable = [
        'seller_id',
        'withdraw_method_id',
        'payout_amount',
        'tax_amount',
        'commission_amount',
        'balance_after_withdraw',
        'status'
    ];

    /**
     * Seller account making the withdraw
     *
     * @return void
     */
    public function seller()
    {
        return $this->belongsTo(SellerAccount::class, 'seller_id');
    }

    /**
     * The payout method of the account
     *
     * @return void
     */
    public function withdrawMethod()
    {
        return $this->belongsTo(WithdrawMethod::class, 'withdraw_method_id');
    }
}
