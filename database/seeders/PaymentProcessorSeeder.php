<?php

namespace Database\Seeders;

use App\Models\Marketplace\PaymentProcessor;
use App\Models\Marketplace\PaymentProcessorType;
use Illuminate\Database\Seeder;

class PaymentProcessorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentProcessors = [
            'stripe' => [
                'card'
            ]
        ];

        foreach($paymentProcessors as $paymentProcessorName => $types) {
            $paymentProcessor = PaymentProcessor::whereName($paymentProcessorName)->first();
            if(!$paymentProcessor) {
                $paymentProcessor = new PaymentProcessor();
                $paymentProcessor->name = $paymentProcessorName;
                $paymentProcessor->slug = $paymentProcessorName;
                $paymentProcessor->save();
            }

            foreach($types as $type) {
                $paymentProcessorType = PaymentProcessorType::whereName($type)->first();
                if(!$paymentProcessorType) {
                    continue;
                }

                $paymentProcessor->types()->attach($paymentProcessorType->id);
            }
        }
    }
}
