<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        $apiKeys = ApiKey::query()->select('id', 'name', 'key')->whereUserId($request->user()->id)->get();
        return response()->json($apiKeys, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $apiKey = new ApiKey();
        $apiKey->user_id = $request->user()->id;
        $apiKey->name = $request->name;
        $apiKey->key = Str::random(64);
        $apiKey->secret = Str::random(128);
        $apiKey->save();

        return response()->json($apiKey, 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $apiKey = ApiKey::whereId($id)->whereUserId($request->user()->id)->first();
        if(!$apiKey) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
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

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}