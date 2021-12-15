<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imagesData = [
            [
                'name' => '',
                'url'  => ''
            ]
        ];

        foreach($imagesData as $imageData) {
            $image = Image::whereName($imageData['name'])->first();
            if(!$image) {
                $image = new Image();
                $image->name = $imageData['name'];
            }

            $image->url = $imageData['url'];
            $image->save();
        }
    }
}
