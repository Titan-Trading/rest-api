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
use Carbon\Carbon;

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
        $apiKeyHeader      = $request->header('ST-API-KEY');

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
                Log::info($ex);
                return response('Unauthorized', 401);
            }
        }
        else if($apiKeyHeader) {
            $timestamp = $request->header('ST-API-TIMESTAMP');
            $signature = $request->header('ST-API-SIGNATURE');

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

            // api key has expired
            // if ($apiKeyRecord->expiration <= Carbon::now()->timestamp) {
            //     return response('Unauthorized', 401);
            // }

            // find user for api key
            $user = User::find($apiKeyRecord->user_id);
            if (!$user) {
                Log::info('No user found');
                return response('Unauthorized', 401);
            }

            // get request body content
            $bodyContent = !empty($request->getContent()) ? $request->getContent() : '';

            // timestamp + method + endpoint + body
            $toEncode = $timestamp . $request->method() . $request->getPathInfo() . $bodyContent;
            $hashed = base64_encode(hash_hmac('sha512', $toEncode, $apiKeyRecord->secret, true));

            // check generated signature against signature sent
            if ($hashed !== $signature) {
                return response('Unauthorized', 401);
            }

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
