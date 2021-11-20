<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                'email'              => 'admin@simpletrader.com',
                'password'           => Hash::make('test123'),
                'profile_image_name' => ''
            ]
        ];

        foreach($usersData as $userData) {
            $profileImage = Image::whereName($userData['profile_image_name'])->first();
            if($profileImage) {
                continue;
            }
            
            $user = User::whereEmail($userData['email'])->first();
            if(!$user) {
                $user = new User();
                $user->email = $userData['email'];
            }

            $user->profile_image_id  = $profileImage->id;
            $user->name              = $userData['name'];
            $user->password          = $userData['password'];
            $user->email_verified_at = Carbon::now();
            $user->remember_token    = '';
            $user->save();
        }
    }
}
