<?php

use Illuminate\Support\Facades\Route;


/**
 * General api routes
 */
Route::group([
    'namespace' => 'General'
], function() {
    /**
     * Images (CRUD)
     */
    Route::get('/images', 'ImageController@index');
    Route::post('/images', 'ImageController@store');
    Route::put('/images/{id}', 'ImageController@update');
    Route::delete('/images/{id}', 'ImageController@delete');

    /**
     * Teams (CRUD)
     */
    // Route::get('/teams', 'TeamController@index');
    // Route::post('/teams', 'TeamController@store');
    // Route::get('/teams/{id}', 'TeamController@show');
    // Route::put('/teams/{id}', 'TeamController@update');
    // Route::delete('/teams/{id}', 'TeamController@delete');

    /**
     * Roles (create, update, index, view, assign permissions)
     */
    Route::get('/roles', 'RoleController@index');
    Route::post('/roles', 'RoleController@store');
    Route::get('/roles/{id}', 'RoleController@show');
    Route::put('/roles/{id}', 'RoleController@update');
    Route::delete('/roles/{id}', 'RoleController@delete');
    Route::post('/roles/{id}/permissions', 'RoleController@assignPermissions');

    /**
     * Permissions
     */
    Route::get('/permissions', 'PermissionController@index');

    /**
     * Users (user accounts)
     */
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');
    Route::get('/users/{id}', 'UserController@show');
    Route::put('/users/{id}', 'UserController@update');
    Route::delete('/users/{id}', 'UserController@delete');

    /**
     * Api keys (used for external api gateway access)
     */
    Route::get('/api-keys', 'ApiKeyController@index');
    Route::post('/api-keys', 'ApiKeyController@store');
    Route::put('/api-keys/{id}', 'ApiKeyController@update');
    Route::delete('/api-keys/{id}', 'ApiKeyController@delete');

    /**
     * Connect tokens (used for external socket gateway access)
     */
    Route::post('/api-connect-tokens', 'ApiConnectTokenController@getOrStore');

    /**
     * Event sourcing (resource, resource id, resource data before and after)
     */
    // Route::get('/events', 'EventSourceController@index');
    // Route::post('/events', 'EventSourceController@store');
    // Route::get('/events/{id}', 'EventSourceController@show');
    // Route::put('/events/{id}', 'EventSourceController@update');
    // Route::delete('/events/{id}', 'EventSourceController@delete');
});