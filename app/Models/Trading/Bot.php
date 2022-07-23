<?php

namespace App\Models\Trading;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory;

    protected $table = 'bots';

    protected $fillable = [
        'user_id',
        'name',
        'algorithm_text',
        'parameter_options'
    ];
}
