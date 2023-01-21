<?php

use Illuminate\Support\Facades\Route;


/**
 * General api routes
 */
Route::group([
    'namespace' => 'General'
], function() {
    /**
     * Details about the current user
     */
    Route::get('/balance', 'CurrentUserController@getBalance');

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
     * User settings
     */
    Route::get('/settings', 'UserSettingController@index');
    Route::put('/settings', 'UserSettingController@update');

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
     * Metrics
     */
    Route::get('/metrics/{name}', 'MetricController@index');
    
    /**
     * Event sourcing (resource, resource id, resource data before and after)
     */
    // Route::get('/events', 'EventSourceController@index');
    // Route::post('/events', 'EventSourceController@store');
    // Route::get('/events/{id}', 'EventSourceController@show');
    // Route::put('/events/{id}', 'EventSourceController@update');
    // Route::delete('/events/{id}', 'EventSourceController@delete');

    /**
     * Support tickets
     */
    Route::get('/support-tickets', 'SupportTicketController@index');
    Route::post('/support-tickets', 'SupportTicketController@store');
    Route::get('/support-tickets/{id}', 'SupportTicketController@show');
    Route::put('/support-tickets/{id}', 'SupportTicketController@update');
    Route::delete('/support-tickets/{id}', 'SupportTicketController@delete');
});