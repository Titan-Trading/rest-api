<?php

namespace App\Http\Controllers\General;

use Ahc\Jwt\JWT;
use App\Http\Controllers\Controller;
use App\Models\ApiConnectToken;
use App\Traits\Tokens;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiConnectTokenController extends Controller
{
    use Tokens;

    public function __construct()
    {
        $this->jwt = new JWT(storage_path('keys/access-token-private.pem'), 'RS512', 300); // 5 mins
    }

    /**
     * Get current connect token or create a new one
     *
     * @param Request $request
     * @return void
     */
    public function getOrStore(Request $request)
    {
        $user = $request->user();

        $apiConnectToken = ApiConnectToken::whereUserId($user->id)
            ->whereRevoked(false)
            ->whereExpiresAt('>', Carbon::now())
            ->first();
        
        // only generate a new one if there's not one that's not expired or revoked
        if(!$apiConnectToken) {

            // generate access token
            $jti = Str::uuid()->toString();
            $expirationDate = Carbon::now()->addDays(100);
            $expiration = $expirationDate->timestamp;
            $audience = 'simple-trader';
            $subject = 'socket-client';
            $metadata = [
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email
            ];
            $accessToken = $this->createAccessToken($jti, $expiration, $audience, $subject, ['user'], $metadata);

            $apiConnectToken = new ApiConnectToken();
            $apiConnectToken->user_id = $request->user()->id;
            $apiConnectToken->access_token = $accessToken;
            $apiConnectToken->expires_at = $expirationDate;
            $apiConnectToken->save();

            return response()->json($apiConnectToken, 201);

        }

        return response()->json($apiConnectToken);
    }
}