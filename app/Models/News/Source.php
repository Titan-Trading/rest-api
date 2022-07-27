<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sources';

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
        return $this->hasOne(Feed::class, 'main_feed_id');
    }

    /**
     * Feeds
     */
    public function feeds()
    {
        return $this->hasMany(Feed::class, 'source_id');
    }
}
