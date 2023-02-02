<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bots';

    protected $fillable = [
        'user_id',
        'name',
        'algorithm_text',
        'algorithm_text_version',
        'algorithm_text_compiled',
        'algorithm_version',
        'parameter_options',
        'event_streams',
        'symbols',
        'indicators'
    ];
}
