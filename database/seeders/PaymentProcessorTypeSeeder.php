<?php

namespace Database\Seeders;

use App\Models\Marketplace\PaymentProcessorType;
use Illuminate\Database\Seeder;

class PaymentProcessorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentProcessorTypes = [
            'card'
        ];

        foreach($paymentProcessorTypes as $paymentProcessorTypeName) {
            $paymentProcessorType = PaymentProcessorType::whereName($paymentProcessorTypeName)->first();
            if(!$paymentProcessorType) {
                $paymentProcessorType = new PaymentProcessorType();
                $paymentProcessorType->name = $paymentProcessorTypeName;
                $paymentProcessorType->save();
            }
        }
    }
}
