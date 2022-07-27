<?php

use Illuminate\Support\Facades\Route;


/**
 * News api routes (articles, signals, etc.)
 */
Route::group([
    'prefix' => 'news',
    'namespace' => 'News'
], function() {
    /**
     * News article categories
     */
    Route::get('/categories', 'CategoryController@index');
    Route::get('/categories/{id}', 'CategoryController@show');

    /**
     * News sources
     */
    Route::get('/sources', 'SourceController@index');
    Route::get('/sources/{id}', 'SourceController@show');

    /**
     * News article authors
     */
    Route::get('/authors', 'AuthorController@index');
    Route::get('/authors/{id}', 'AuthorController@show');

    /**
     * News articles
     */
    Route::get('/articles', 'ArticleController@index');
    Route::get('/articles/{id}', 'ArticleController@show');
});