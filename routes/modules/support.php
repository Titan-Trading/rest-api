<?php

use Illuminate\Support\Facades\Route;


/**
 * General api routes
 */
Route::group([
    'prefix' => 'support',
    'namespace' => 'Support'
], function() {
    /**
     * Support tickets
     */
    Route::get('/tickets', 'TicketController@index');
    Route::post('/tickets', 'TicketController@store');
    Route::get('/tickets/{id}', 'TicketController@show');
    Route::put('/tickets/{id}', 'TicketController@update');
    Route::delete('/tickets/{id}', 'TicketController@delete');
});