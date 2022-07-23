<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    protected $fillable = [
        'source_id',
        'name'
    ];

    /**
     * News source
     */
    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }
}
