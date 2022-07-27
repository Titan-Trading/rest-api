<?php

namespace App\Http\Controllers\Admin\General;

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
        $users = User::query()->get();

        return response()->json($users);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'profile_image_id' => 'required|exists:images,id'
        ], [
            'name_required' => 'Name is required',
            'email_required' => 'Email is required',
            'email_unique' => 'Email is not unique',
            'password_required' => 'Password is required',
            'password_confirm_required' => 'Password confirmation is required',
            'password_confirm_same' => 'Passwords do not match',
            'profile_image_id_required' => 'Profile image id is required',
            'profile_image_id_exists' => 'Profile image does not exist'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->remember_token = '';
        $user->profile_image_id = $request->profile_image_id;
        $user->save();

        return response()->json($user, 201);
    }

    /**
     * Get user by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json($user);
    }

    /**
     * Update user by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $emailRules = ['required'];
        if($request->email == $user->email) {
            $emailRules[] = 'unique:users,email';
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => $emailRules,
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'profile_image_id' => 'required|exists:images,id'
        ], [
            'name_required' => 'Name is required',
            'email_required' => 'Email is required',
            'email_unique' => 'Email is not unique',
            'password_required' => 'Password is required',
            'password_confirm_required' => 'Password confirmation is required',
            'password_confirm_same' => 'Passwords do not match',
            'profile_image_id_required' => 'Profile image id is required',
            'profile_image_id_exists' => 'Profile image does not exist'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->remember_token = '';
        $user->profile_image_id = $request->profile_image_id;
        $user->default_payment_method_id = $request->default_payment_method_id ? $request->default_payment_method_id : null;
        $user->save();

        return response()->json($user);
    }

    /**
     * Delete user by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json($user);
    }
}
