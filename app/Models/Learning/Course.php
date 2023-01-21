<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'image_id',
        'user_id',
        'slug',
        'title',
        'subtitle',
        'description',
        'requirements',
        'price',
        'rating',
        'level'
    ];
}