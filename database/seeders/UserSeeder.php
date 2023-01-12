<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Role;
use App\Models\Trading\Exchange;
use App\Models\Trading\ExchangeAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = [
            [
                'name'               => 'Administrator',
                'role'               => 'Administrator',
                'email'              => 'admin@simpletrader.com',
                'password'           => 'test123',
                'profile_image_name' => 'test'
            ],
            [
                'name'               => 'Exchange Listener',
                'role'               => 'Administrator',
                'email'              => 'exchange.listener@simpletrader.com',
                'password'           => 'test123',
                'profile_image_name' => 'test'
            ],
            [
                'name'               => 'Socket Gateway',
                'role'               => 'Administrator',
                'email'              => 'socket.gateway@simpletrader.com',
                'password'           => 'test123',
                'profile_image_name' => 'test'
            ],
            [
                'name'               => 'Backtester',
                'role'               => 'Administrator',
                'email'              => 'backtester@simpletrader.com',
                'password'           => 'test123',
                'profile_image_name' => 'test'
            ]
        ];

        foreach($usersData as $userData) {
            // $profileImage = Image::whereName($userData['profile_image_name'])->first();
            // if($profileImage) {
            //     continue;
            // }


            $role = Role::whereName($userData['role'])->first();
            if(!$role) {
                continue;
            }
            
            $user = User::whereEmail($userData['email'])->first();
            if(!$user) {
                $user = new User();
                $user->email = $userData['email'];
            }

            $user->role_id           = $role->id;
            $user->profile_image_id  = null; //$profileImage->id;
            $user->name              = $userData['name'];
            $user->password          = Hash::make($userData['password']);
            $user->email_verified_at = Carbon::now();
            $user->remember_token    = Str::random(24);
            $user->save();

            // if user automatically add simpletrader exchange
            if($userData['name'] == 'Administrator') {
                $exchangeAccount = ExchangeAccount::whereUserId($user->id)->whereHas('exchange', function($q) {
                    $q->whereName('SimpleTrader');
                })->first();
                if(!$exchangeAccount) {
                    $exchange = Exchange::whereName('SimpleTrader')->first();

                    $exchangeAccount = new ExchangeAccount();
                    $exchangeAccount->exchange_id = $exchange->id;
                    $exchangeAccount->user_id = $user->id;
                    $exchangeAccount->api_key = 'demo';
                    $exchangeAccount->api_key_secret = 'demo';
                    $exchangeAccount->save();
                }
            }
        }
    }
}
