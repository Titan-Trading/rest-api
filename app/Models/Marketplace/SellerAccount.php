<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_accounts';

    protected $fillable = [
        'owner_id',
        'default_withdraw_method_id',
        'name',
        'status',
        'rating',
        'balance',
        'commission_generated',
        'revenue_generated',
        'balance_updated_at'
    ];

    /**
     * User that owns the account
     *
     * @return void
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * The defualt payout method for the account
     *
     * @return void
     */
    public function defaultWithdrawMethod()
    {
        return $this->hasOne(WithdrawMethod::class, 'id', 'default_withdraw_method_id');
    }

    /**
     * The defualt payout method for the account
     *
     * @return void
     */
    public function withdrawMethods()
    {
        return $this->hasMany(WithdrawMethod::class, 'seller_id');
    }
}
