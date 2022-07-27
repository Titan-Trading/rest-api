<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        $apiKeys = ApiKey::query()->select('id', 'name', 'key')->whereUserId($request->user()->id)->get();
        
        return response()->json($apiKeys);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name_required' => 'Name is required'
        ]);

        $apiKey = new ApiKey();
        $apiKey->user_id = $request->user()->id;
        $apiKey->name = $request->name;
        $apiKey->key = Str::random(64);
        $apiKey->secret = Str::random(128);
        $apiKey->save();

        return response()->json($apiKey, 201);
    }

    public function update(Request $request, $id)
    {
        $apiKey = ApiKey::whereId($id)->whereUserId($request->user()->id)->first();
        if(!$apiKey) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required'
        ], [
            'name_required' => 'Name is required'
        ]);

        return response()->json($apiKey);
    }

    public function delete(Request $request, $id)
    {
        $apiKey = ApiKey::find($id);
        if(!$apiKey) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $apiKey->delete();

        return response()->json($apiKey);
    }
}