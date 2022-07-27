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
    Route::post('/categories', 'CategoryController@store');
    Route::get('/categories/{id}', 'CategoryController@show');
    Route::put('/categories/{id}', 'CategoryController@update');
    Route::delete('/categories/{id}', 'CategoryController@delete');

    /**
     * News sources
     */
    Route::get('/sources', 'SourceController@index');
    Route::post('/sources', 'SourceController@store');
    Route::get('/sources/{id}', 'SourceController@show');
    Route::put('/sources/{id}', 'SourceController@update');
    Route::delete('/sources/{id}', 'SourceController@delete');

    /**
     * News article authors
     */
    Route::get('/authors', 'AuthorController@index');
    Route::post('/authors', 'AuthorController@store');
    Route::get('/authors/{id}', 'AuthorController@show');
    Route::put('/authors/{id}', 'AuthorController@update');
    Route::delete('/authors/{id}', 'AuthorController@delete');

    /**
     * News articles
     */
    Route::get('/articles', 'ArticleController@index');
    Route::post('/articles', 'ArticleController@store');
    Route::get('/articles/{id}', 'ArticleController@show');
    Route::put('/articles/{id}', 'ArticleController@update');
    Route::delete('/articles/{id}', 'ArticleController@delete');
});