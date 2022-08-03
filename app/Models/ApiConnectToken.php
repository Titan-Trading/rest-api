<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiConnectToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'api_connect_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'revoked'
    ];
}