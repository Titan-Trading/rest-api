<?php
namespace App\Services;

use App\Services\MessageBus;
use Illuminate\Support\Str;

class Log
{
    protected $messageBus;
    protected $instanceId;

    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;

        $this->instanceId = env('INSTANCE_ID');
    }

    public function info(string $message, string $title = '', string $category = '')
    {
        $this->messageBus->sendMessage('system-logs', [
            'topic' => 'service-registry',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'ADD',
            'serviceId' => 'simple-trader-api',
            'instanceId' => $this->instanceId,
            'title' => $title,
            'category' => $category,
            'message' => $message
        ]);
    }

    public function error(string $message, string $title = '', string $category = '')
    {
        $this->messageBus->sendMessage('system-logs', [
            'topic' => 'service-registry',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'ADD',
            'serviceId' => 'simple-trader-api',
            'instanceId' => $this->instanceId,
            'title' => $title,
            'category' => $category,
            'message' => $message
        ]);
    }

    public function debug(string $message, string $title = '', string $category = '')
    {
        $this->messageBus->sendMessage('system-logs', [
            'topic' => 'service-registry',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'ADD',
            'serviceId' => 'simple-trader-api',
            'instanceId' => $this->instanceId,
            'title' => $title,
            'category' => $category,
            'message' => $message
        ]);
    }
}