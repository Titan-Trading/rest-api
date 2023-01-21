<?php

namespace App\Models;

use App\Models\Marketplace\PaymentMethod;
use App\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'role_id',
        'profile_image_id',
        'default_payment_method_id',
        'name',
        'email',
        'password',
        'email_verified_at',
        'balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'balance'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get role for a user
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * Profile image for a user
     */
    public function profileImage()
    {
        return $this->hasOne(Image::class, 'id', 'profile_image_id');
    }

    /**
     * Default method of payment for a user
     *
     * @return void
     */
    public function defaultPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'default_payment_method_id');
    }

    /**
     * Get payment methods for a user
     *
     * @return void
     */
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'user_id');
    }
}
