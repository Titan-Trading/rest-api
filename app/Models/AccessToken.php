<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory;

    protected $table = 'access_tokens';

    protected $fillable = [
        'jti',
        'user_id',
        'audience',
        'expiration',
        'subject',
        'access_token',
        'refresh_token',
        'revoked'
    ];
}
