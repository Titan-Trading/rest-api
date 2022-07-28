<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List users
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $users = User::whereId($request->user()->id)->get();

        return response()->json($users);
    }
}
