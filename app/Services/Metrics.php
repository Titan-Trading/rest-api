<?php
namespace App\Services;

use App\Services\MessageBus;
use Illuminate\Support\Str;

class Metrics
{
    protected $messageBus;
    protected $instanceId;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;

        $this->instanceId = env('INSTANCE_ID');
    }

    public static function generate(string $name)
    {
        switch($name) {
            case 'total-sales':
                return 100;
            case 'overall-pnl':
                return 90;
            case 'bootcamp-completion':
                return 12;
            case 'pnl-by-strategy':
                return [
                    [
                        'id' => 1,
                        'name' => 'Strategy 1',
                        'author' => 'User 1',
                        'trades' => 60,
                        'profitable_trades' => 60,
                        'losing_trades' => 0,
                        'status' => 'active'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Strategy 2',
                        'author' => 'User 1',
                        'trades' => 100,
                        'profitable_trades' => 90,
                        'losing_trades' => 10,
                        'status' => 'active'
                    ]
                ];
            case 'exchange-account-balances':
                return [
                    [
                        'exchange_account_id' => 1,
                        'exchange_account' => 'Exchange Account 1',
                        'total' => 1.00,
                        'locked' => 0.10,
                        'free' => 0.90
                    ],
                    [
                        'exchange_account_id' => 2,
                        'exchange_account' => 'Exchange Account 2',
                        'total' => 2.00,
                        'locked' => 0.10,
                        'free' => 1.90
                    ],
                ];
            case 'profit-by-exchange':
                return [
                    [
                        'id' => 1,
                        'name' => 'Exchange 1',
                        'profit' => 289348.34
                    ],
                    [
                        'id' => 2,
                        'name' => 'Exchange 2',
                        'profit' => 100000.29
                    ],
                ];
        }
    }
}