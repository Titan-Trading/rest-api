<?php
namespace App\Traits;

use Carbon\Carbon;

trait AccessTokens
{
    /**
     * Create an access token
     */
    public function createToken($jti, $expiration, $audience, $subject, array $scopes)
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