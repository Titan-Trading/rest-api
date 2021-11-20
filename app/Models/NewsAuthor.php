<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsAuthor extends Model
{
    use HasFactory;

    protected $table = 'news_authors';

    protected $fillable = [
        'source_id',
        'name'
    ];

    /**
     * News source
     */
    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }
}
