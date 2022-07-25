<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feed extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'feeds';

    protected $fillable = [
        'source_id',
        'name',
        'url'
    ];

    /**
     * News source
     */
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }
}
