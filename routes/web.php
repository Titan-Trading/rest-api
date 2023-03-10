<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'You should not be here. :)'
    ]);
});

/**
 * Health check api route
 */
Route::get('/health', function() {
    return response()->json([
        'message' => 'System is all good'
    ], 200);
});
