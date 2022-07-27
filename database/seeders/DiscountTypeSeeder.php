<?php

namespace Database\Seeders;

use App\Models\Marketplace\DiscountType;
use Illuminate\Database\Seeder;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $discountTypes = [
            'percent',
            'dollar'
        ];

        foreach($discountTypes as $discountTypeName) {
            $discountType = DiscountType::whereName($discountTypeName)->first();
            if(!$discountType) {
                $discountType = new DiscountType();
                $discountType->name = $discountTypeName;
                $discountType->save();
            }
        }
    }
}
