<?php

namespace App\Console\Commands;

use App\Services\MessageBus;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RegisterServiceCommand extends Command
{
    private $messageBus = null;

    protected $signature = 'service:up';
    protected $description = 'Register the service and api endpoints';

    // private const TOPIC_CONF_AUTO_COMMIT_INTERVAL = '5000';
    // private const TOPIC_CONF_OFFSET_STORE_METHOD = 'file';  // file|broker
    // private const TOPIC_CONF_OFFSET_RESET = 'smallest';  // smallest|largest

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MessageBus $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // add service registry message to kafka message bus
        $this->info('Register api with micro-service system');

        $instanceId = env('INSTANCE_ID');

        $routeCollection = Route::getRoutes();

        $endPoints = [];

        foreach ($routeCollection as $value) {
            foreach($value->methods() as $method) {
                $url = '/' . $value->uri();

                $routePattern = '^' . str_replace('/', '\/', $url);

                if(strpos($value->uri(), '{')) {
                    $routePattern = '^' . preg_replace('#\{.*?\}#s', '(?:([^\/]+?))', str_replace('/', '\/', $url)) . '?$';
                }

                $this->info($method . ' ' . $routePattern);

                $endPoints[] = [
                    'url' => $routePattern,
                    'method' => strtolower($method)
                ];
            }
        }

        try {
            $this->messageBus->sendMessage('service-registry', [
                'topic' => 'service-registry',
                'messageType' => 'EVENT',
                'messageId' => Str::uuid()->toString(),
                'eventId' => 'SERVICE_ONLINE',
                'serviceId' => 'simple-trader-api',
                'instanceId' => $instanceId,
                'supportedCommunicationChannels' => ['rest'],
                'hostname' => 'api-proxy',
                'port' => 8001,
                'endpoints' => $endPoints,
                'commands' => []
            ]);

            $this->info('Added service to registry (' . count($endPoints) . ' routes)!');

        }
        catch(Exception $ex)
        {
            $this->error($ex);
        }

        return Command::SUCCESS;
    }
}
