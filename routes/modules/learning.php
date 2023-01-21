<?php

use Illuminate\Support\Facades\Route;


/**
 * Learning api routes
 */
Route::group([
    'prefix' => 'learning',
    'namespace' => 'Learning'
], function () {
    /**
     * Courses (CRUD)
     */
    Route::get('/courses', 'CourseController@index');

});