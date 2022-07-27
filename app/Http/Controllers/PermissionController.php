<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Get list of system-wide permissions
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }
}