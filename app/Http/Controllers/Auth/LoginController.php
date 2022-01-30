<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ahc\Jwt\JWT;
use App\Models\RefreshToken;
use App\Models\User;
use App\Traits\AccessTokens;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use AccessTokens;
    
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWT(storage_path('keys/access-token-private.pem'), 'RS512', 300); // 5 mins
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        // convert email to lowercase
        $request->email = strtolower($request->email);

        // get user by email 
        $user = User::whereEmail($request->email)->first();

        // user not found by email or wrong password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response('Unauthorized', 401);
        }

        // generate access token
        $jti = Str::uuid()->toString();
        $expiration = Carbon::now()->addSeconds(300)->timestamp;
        $audience = 'simple-trader';
        $subject = 'user';
        $metadata = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
        $accessToken = $this->createAccessToken($jti, $expiration, $audience, $subject, ['user'], $metadata);
        
        // generate refresh token
        $refreshToken = Str::random(1024);

        // revoke all previous refresh tokens for a user
        RefreshToken::whereUserId($user->id)->update(['revoked' => true]);

        // store token pair
        RefreshToken::create([
            'user_id' => $user->id,
            'expiration' => $expiration,
            'refresh_token' => $refreshToken,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);

        return response([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ], 200);
    }

    public function logout(Request $request)
    {
        $accessToken = $request->header('X-Auth-Token');

        // no access token
        if (!$accessToken) {
            return response('Unauthorized', 401);
        }

        try {
            // decode, check expiration (throws exception)
            $jwtData = $this->jwt->decode($accessToken);

            // no metadata or user id
            if(!isset($jwtData['metadata']) || !isset($jwtData['metadata']->user_id)) {
                return response('Unauthorized', 401);
            }

            // revoke all refresh tokens for a user
            RefreshToken::whereUserId($jwtData['metadata']->user_id)->update(['revoked' => true]);

            return response('OK', 200);
        }
        catch(Exception $ex) {
            Log::info($ex->getMessage());
            return response('Unauthorized', 401);
        }
    }

    public function refresh(Request $request)
    {
        $accessToken = $request->header('X-Auth-Token');

        // no access token
        if (!$accessToken) {
            return response('Unauthorized', 401);
        }

        $refreshToken = $request->get('refresh_token');

        // no refresh token
        if (!$request->has('refresh_token') || !$refreshToken) {
            return response('Unauthorized', 401);
        }

        try {
            // decode, check expiration (throws exception)
            $jwtData = $this->jwt->decode($accessToken);

            // no metadata or user id
            if(!isset($jwtData['metadata']) || !isset($jwtData['metadata']->user_id)) {
                return response('Unauthorized', 401);
            }
        }
        catch(Exception $ex) {
            Log::info($ex->getMessage());
            return response('Unauthorized', 401);
        }

        $tokenRecord = RefreshToken::whereRefreshToken($refreshToken)->first();

        // no access token found for the refresh token
        if (!$tokenRecord) {
            return response('Unauthorized', 401);
        }

        // token has been revoked
        if ($tokenRecord->revoked) {
            return response('Unauthorized', 401);
        }

        $user = User::find($tokenRecord->user_id);

        // no user found for the access token
        if (!$user) {
            return response('Unauthorized', 401);
        }

        try {
            // generate access token
            $jti = Str::uuid()->toString();
            $expiration = Carbon::now()->addSeconds(300)->timestamp;
            $audience = 'https://www.simpletrader.com';
            $subject = 'user';
            $metadata = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
            $accessToken = $this->createAccessToken($jti, $expiration, $audience, $subject, ['user'], $metadata);

            // generate refresh token
            $refreshToken = Str::random(1024);

            // revoke all previous refresh tokens for a user
            RefreshToken::whereUserId($user->id)->update(['revoked' => true]);

            // store token pair
            RefreshToken::create([
                'user_id' => $user->id,
                'expiration' => $expiration,
                'refresh_token' => $refreshToken,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]);

            return response()->json([
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ], 200);
        }
        catch (Exception $ex) {
            Log::info($ex->getMessage());
            return response('Unauthorized', 401);
        }
    }
}