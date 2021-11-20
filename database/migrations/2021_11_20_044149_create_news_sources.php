<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Categories of news articles
         */
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        /**
         * Authors of news articles
         */
        Schema::create('news_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id');
            $table->string('name');
            $table->timestamps();
        });

        /**
         * Sources for news articles
         */
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logo_id')->nullable();
            $table->foreignId('main_feed_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('website_url');
            $table->timestamps();
        });

        /**
         * Feeds used to aggregate news articles
         */
        Schema::create('news_feeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id');
            $table->string('name');
            $table->text('url')->nullable();
            $table->timestamps();
        });

        /**
         * News articles that were aggregated
         */
        Schema::create('news_articles', function(Blueprint $table) {
            $table->id();
            $table->foreignId('source_id');
            $table->foreignId('feed_id');
            $table->foreignId('category_id');
            $table->foreignId('author_id');
            $table->string('url');
            $table->string('title');
            $table->text('excerpt');
            $table->longText('content_html');
            $table->longText('content_text');
            $table->timestamp('published_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_articles');
        Schema::dropIfExists('news_feeds');
        Schema::dropIfExists('news_sources');
        Schema::dropIfExists('news_authors');
        Schema::dropIfExists('news_categories');
    }
}
