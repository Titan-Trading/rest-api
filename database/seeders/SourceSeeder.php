<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\News\Feed;
use App\Models\News\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newsSourcesData = [
            [
                'logo_name' => '',
                'name' => '',
                'description' => '',
                'website_url' => '',
                'main_feed' => [
                    'name' => '',
                    'url' => ''
                ],
                'feeds' => [
                    '' => ''
                ],
            ]
        ];

        foreach($newsSourcesData as $newsSourceData) {
            $logoImage = Image::whereName($newsSourceData['logo_name'])->first();
            if(!$logoImage || is_null($logoImage->id)) {
                continue;
            }

            $newsSource = Source::whereName($newsSourceData['name'])->first();
            if($newsSource) {
                $newsSource = new Source();
                $newsSource->name = $newsSourceData['name'];
            }

            $newsSource->logo_id     = $logoImage->id;
            $newsSource->description = $newsSourceData['description'];
            $newsSource->website_url = $newsSourceData['website_url'];
            $newsSource->save();

            $mainFeed = Feed::whereName($newsSourceData['main_feed']['name'])->first();
            if(!$mainFeed) {
                $mainFeed = new Feed();
                $mainFeed->source_id = $newsSource->id;
                $mainFeed->name      = $newsSourceData['main_feed']['name'];
            }

            $mainFeed->url = $newsSourceData['main_feed']['url'];
            $mainFeed->save();

            $newsSource->update([
                'main_feed_id' => $mainFeed->id
            ]);

            foreach($newsSourceData['feeds'] as $name => $url) {
                $feed = Feed::whereName($name)->first();
                if(!$feed) {
                    $feed = new Feed();
                    $feed->source_id = $newsSource->id;
                    $feed->name      = $name;
                }

                $feed->url = $url;
                $feed->save();
            }
        }
    }
}
