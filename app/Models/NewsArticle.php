<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    use HasFactory;

    protected $table = 'news_articles';

    protected $fillable = [
        'source_id',
        'feed_id',
        'category_id',
        'author_id',
        'url',
        'title',
        'excerpt',
        'content_html',
        'content_text',
        'published_at'
    ];

    /**
     * News source
     */
    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'source_id');
    }

    /**
     * News category
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    /**
     * News feed
     */
    public function feed()
    {
        return $this->belongsTo(NewsFeed::class, 'feed_id');
    }

    /**
     * News author
     */
    public function author()
    {
        return $this->belongsTo(NewsAuthor::class, 'author_id');
    }
}
