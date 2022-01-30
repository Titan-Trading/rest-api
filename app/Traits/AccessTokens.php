<?php
namespace App\Traits;

use Carbon\Carbon;

trait AccessTokens
{
    /**
     * Create an access token
     */
    public function createAccessToken($jti, $expiration, $audience, $subject, array $scopes, array $metadata = null)
    {
        return $this->jwt->encode(array_merge([
            'jti' => $jti,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiration,
            'aud' => $audience,
            'iss' => env('APP_URL'),
            'scopes' => $scopes,
            'sub' => $subject,
        ], ['metadata' => $metadata]));
    }
}