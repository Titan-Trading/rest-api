<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

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
        return $this->belongsTo(Source::class, 'source_id');
    }

    /**
     * News category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * News feed
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class, 'feed_id');
    }

    /**
     * News author
     */
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
