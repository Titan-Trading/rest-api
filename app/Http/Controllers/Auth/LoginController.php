<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ahc\Jwt\JWT;
use App\Models\AccessToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWT(env('APP_KEY'), 'HS512', 3600 * 4);
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
        $expiration = Carbon::now()->addSeconds(3600 * 4)->timestamp;
        $audience = 'https://www.hometownticketing.com';
        $subject = 'user';
        $accessToken = $this->createToken($jti, $expiration, $audience, $subject, ['user']);

        // generate refresh token
        $refreshToken = Str::random(512);

        // revoke all previous access tokens for a user
        AccessToken::whereUserId($user->id)->update(['revoked' => true]);

        // store token pair
        AccessToken::create([
            'user_id' => $user->id,
            'jti' => $jti,
            'expiration' => $expiration,
            'audience' => $audience,
            'subject' => $subject,
            'access_token' => $accessToken,
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

        $tokenRecord = AccessToken::whereAccessToken($accessToken)->first();

        // no access token foun
        if (!$tokenRecord) {
            return response('Unauthorized', 401);
        }

        // revoke all previous access tokens for a user
        AccessToken::whereUserId($tokenRecord->user_id)->update(['revoked' => true]);

        return response()->json('Success', 200);
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
        if (!$refreshToken) {
            return response('Unauthorized', 401);
        }

        $tokenRecord = AccessToken::whereRefreshToken($refreshToken)->first();

        // no access token found for the refresh token
        if (!$tokenRecord) {
            return response('Unauthorized', 401);
        }

        // token has been revoked
        if ($tokenRecord->revoked) {
            return response('Unauthorized', 401);
        }

        // access token does not match the access token used to generate the refresh token
        if ($accessToken != $tokenRecord->access_token) {
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
            $expiration = Carbon::now()->addSeconds(3600 * 4)->timestamp;
            $audience = 'https://www.simpletrader.com';
            $subject = 'user';
            $accessToken = $this->createToken($jti, $expiration, $audience, $subject, ['user']);

            // generate refresh token
            $refreshToken = Str::random(512);

            // revoke all previous access tokens for a user
            AccessToken::whereUserId($user->id)->update(['revoked' => true]);

            // store token pair
            AccessToken::create([
                'user_id' => $user->id,
                'jti' => $jti,
                'expiration' => $expiration,
                'audience' => $audience,
                'subject' => $subject,
                'access_token' => $accessToken,
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
            return response(trans('messages.responses.401'), 401);
        }
    }

    /**
     * Create an access token
     */
    private function createToken($jti, $expiration, $audience, $subject, array $scopes)
    {
        return $this->jwt->encode([
            'jti' => $jti,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiration,
            'aud' => $audience,
            'iss' => env('APP_URL'),
            'scopes' => $scopes,
            'sub' => $subject,
        ]);
    }
}