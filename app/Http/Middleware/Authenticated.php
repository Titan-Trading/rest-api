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
                $jwt = new JWT(storage_path('keys/access-token-private.pem'), 'RS512', 300); // 5 mins

                // decode, check expiration (throws exception)
                $jwtData = $jwt->decode($accessTokenHeader);

                // no metadata or user id
                if(!isset($jwtData['metadata']) || !isset($jwtData['metadata']->user_id)) {
                    return response('Unauthorized', 401);
                }

                $user = User::find($jwtData['metadata']->user_id);
                if (!$user) {
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
