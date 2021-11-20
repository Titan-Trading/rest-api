<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    use HasFactory;

    protected $table = 'news_sources';

    protected $fillable = [
        'logo_id',
        'main_feed_id',
        'name',
        'description',
        'website_url'
    ];

    /**
     * Logo image
     */
    public function logoImage()
    {
        return $this->hasOne(Image::class, 'logo_id');
    }

    /**
     * Main feed
     */
    public function mainFeed()
    {
        return $this->hasOne(NewsFeed::class, 'main_feed_id');
    }

    /**
     * Feeds
     */
    public function feeds()
    {
        return $this->hasMany(NewsFeed::class, 'source_id');
    }
}
