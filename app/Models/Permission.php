<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissions';

    protected $fillable = [
        'role_id',
        'name',
        'resource',
        'action',
        'description'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
