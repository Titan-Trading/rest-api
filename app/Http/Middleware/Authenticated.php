<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ahc\Jwt\JWT;
use App\Models\ApiKey;

class Authenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // check for custom auth headers
        $accessTokenHeader = $request->header('X-Auth-Token');
        $apiKeyHeader      = $request->header('X-Api-Key');

        if ($accessTokenHeader) {
            try {
                $jwt = new JWT(env('APP_KEY'), 'HS512', 3600 * 4);

                // decode, check expiration
                $decodedJWT = $jwt->decode($accessTokenHeader);

                // get access token from database
                $tokenRecord = AccessToken::whereAccessToken($accessTokenHeader)->first();

                // no record found
                if (!$tokenRecord) {
                    Log::info('No JWT record found');
                    return response('Unauthorized', 401);
                }

                // token was revoked
                if ($tokenRecord->revoked) {
                    Log::info('JWT revoked');
                    return response('Unauthorized', 401);
                }

                $user = User::find($tokenRecord->user_id);
                if (!$user) {
                    Log::info('No user found');
                    return response('Unauthorized', 401);
                }

                // bind user to request
                $request->merge(['user' => $user]);
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
            catch (Exception $ex) {
                Log::info($ex->getMessage());
                return response('Unauthorized', 401);
            }
        }
        else if($apiKeyHeader) {
            // get api key from database
            $apiKeyRecord = ApiKey::where('key', $apiKeyHeader)->first();

            // no record found
            if (!$apiKeyRecord) {
                Log::info('No api key record found');
                return response('Unauthorized', 401);
            }

            // api key was revoked
            if ($apiKeyRecord->revoked) {
                Log::info('api key revoked');
                return response('Unauthorized', 401);
            }

            // find user for api key
            $user = User::find($apiKeyRecord->user_id);
            if (!$user) {
                Log::info('No user found');
                return response('Unauthorized', 401);
            }

            // verify request signature (base 64 encoded timestamp+method+endpoint+body with sha256)

            // bind user to request
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }
        else {
            Log::info('No access token or api key found');
            return response('Unauthorized', 401);
        }

        return $next($request);
    }
}
