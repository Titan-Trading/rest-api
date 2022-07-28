<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/**
 * Authentication routes (signup, login, log out, refresh access token, password reset, etc.)
 */
Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function() {
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');
    Route::post('/refresh', 'LoginController@refresh');
});


/**
 * Authenticated api routes
 */
Route::middleware(['auth', 'email.verified'])->group(function() {

    /**
     * Include all admin api route modules
     */
    require_once base_path('routes/modules/admin/index.php');

    /**
     * Include all non-admin api route modules
     */
    require_once base_path('routes/modules/index.php');

});
