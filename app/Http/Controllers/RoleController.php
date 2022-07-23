<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * List of roles
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $roles = Role::query()->with(['permissions'])->get();

        return response()->json($roles);
    }

    /**
     * Create a role
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name'
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name must be unique'
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->description = isset($request->description) ? $request->description : null;
        $role->save();

        return response()->json($role, 201);
    }

    /**
     * Get a single role by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $role = Role::whereId($id)->with('permissions')->first();

        if(!$role) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($role);
    }

    /**
     * Update a role by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $role = Role::whereId($id)->with('permissions')->first();

        if(!$role) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($role->name !== $request->name) {
            $nameRules[] = 'unique:roles,name';
        }

        $this->validate($request, [
            'name' => $nameRules
        ]);

        $role->name = $request->name;
        $role->description = isset($request->description) ? $request->description : $role->description;
        $role->save();

        return response()->json($role);
    }

    /**
     * Delete a role by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $role = Role::whereId($id)->with('permissions')->first();

        if(!$role) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $role->delete();

        return response()->json($role);
    }

    /**
     * Assign permissions to a role by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function assignPermissions(Request $request, $id)
    {
        $role = Role::whereId($id)->with('permissions')->first();

        if(!$role) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'permission_ids' => 'array|min:1'
        ], [
            'permission_ids_array' => 'Permission ids must be an array',
            'permission_ids_min' => 'Must have at least one permission id'
        ]);

        $role->permissions()->sync($request->permission_ids);

        return response()->json($role);
    }
}