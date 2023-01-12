<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $apiKeys = [
            [
                'email' => 'exchange.listener@simpletrader.com',
                'name' => 'Exchange Listener',
                'key' => 'bW8WMv6oWO8KMCpET4g0a0QevKZAqEcFRcU7uNE9yW6YRugH7Yv1yynjuWMP3qG3',
                'secret' => 'aUXcbaS8lTvdkoVHKN3yT5f2rporGfd7u5QdHnZNW0At6otPFJM9ARI2mwTnpmwlNIK0oyUK2SWMBAIwtE5Iaiwlhhe14EjhgwzwHIrZfU1xVcrCIF09MOcAaWU1hyO7'
            ],
            [
                'email' => 'socket.gateway@simpletrader.com',
                'name' => 'Socket Gateway',
                'key' => 'bW8WMv6oWO8KMCpET4g0a0QevKZAqEcFRcU7uNE9yW6YRugH7Yv1yynjuWMP3qG4',
                'secret' => 'aUXcbaS8lTvdkoVHKN3yT5f2rporGfd7u5QdHnZNW0At6otPFJM9ARI2mwTnpmwlNIK0oyUK2SWMBAIwtE5Iaiwlhhe14EjhgwzwHIrZfU1xVcrCIF09MOcAaWU1hyO8'
            ],
            [
                'email' => 'backtester@simpletrader.com',
                'name' => 'Backtester',
                'key' => 'bW8WMv6oWO8KMCpET4g0a0QevKZAqEcFRcU7uNE9yW6YRugH7Yv1yynjuWMP3qG5',
                'secret' => 'aUXcbaS8lTvdkoVHKN3yT5f2rporGfd7u5QdHnZNW0At6otPFJM9ARI2mwTnpmwlNIK0oyUK2SWMBAIwtE5Iaiwlhhe14EjhgwzwHIrZfU1xVcrCIF09MOcAaWU1hyO9'
            ]
        ];

        foreach($apiKeys as $apiKeyData) {

            $user = User::whereEmail($apiKeyData['email'])->first();
            if(!$user) {
                continue;
            }

            $apiKey = ApiKey::whereUserId($user->id)->whereName($apiKeyData['name'])->first();
            if(!$apiKey) {
                $apiKey = new ApiKey();
                $apiKey->user_id = $user->id;
                $apiKey->name = $apiKeyData['name'];
                $apiKey->key = isset($apiKeyData['key']) ? $apiKeyData['key'] : Str::random(64);
                $apiKey->secret = isset($apiKeyData['secret']) ? $apiKeyData['secret'] : Str::random(128);
                $apiKey->save();
            }
        }
    }
}
