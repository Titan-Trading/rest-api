<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    use HasFactory;

    protected $table = 'news_feeds';

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
        return $this->belongsTo(NewsSource::class, 'source_id');
    }
}
