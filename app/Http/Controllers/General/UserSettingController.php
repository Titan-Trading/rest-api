<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = $request->user();
        // $user->load('role', 'profileImage', 'defaultPaymentMethod');

        return response()->json($user);
    }
}