<?php

use Illuminate\Support\Facades\Route;


/**
 * Admin api routes
 */
Route::group([
    'middleware' => ['admin'],
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function() {

    /**
     * Include admin route modules
     */
    require_once base_path('routes/modules/admin/general.php');
    require_once base_path('routes/modules/admin/trading.php');
    require_once base_path('routes/modules/admin/news.php');
    require_once base_path('routes/modules/admin/marketplace.php');
    
});