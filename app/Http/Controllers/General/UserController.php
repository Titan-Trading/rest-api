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
        $users = User::select('id', 'role_id', 'profile_image_id', 'default_payment_method_id', 'name', 'email', 'email_verified_at')
            ->with(['role' => function($q) {
                $q->select('id', 'name', 'description');
            }])
            ->whereId($request->user()->id)
            ->get();

        return response()->json($users);
    }
}
