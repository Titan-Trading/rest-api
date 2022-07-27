<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'refresh_tokens';

    protected $fillable = [
        'user_id',
        'expiration',
        'refresh_token',
        'revoked'
    ];
}
