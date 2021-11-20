<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $table = 'exchanges';

    protected $fillable = [
        'logo_id',
        'name',
        'website_url',
        'is_active',
        'is_dex'
    ];

    /**
     * Logo image
     */
    public function logoImage()
    {
        return $this->hasOne(Image::class, 'logo_id');
    }
}
