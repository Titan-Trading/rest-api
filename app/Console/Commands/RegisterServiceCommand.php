<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use RdKafka;

class RegisterServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register the service and api endpoints';

    private const TOPIC_CONF_AUTO_COMMIT_INTERVAL = '5000';
    private const TOPIC_CONF_OFFSET_STORE_METHOD = 'file';  // file|broker
    private const TOPIC_CONF_OFFSET_RESET = 'smallest';  // smallest|largest

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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

            if(strpos($value->uri(), 'api') === false) {
                continue;
            }

            foreach($value->methods() as $method) {
                $url = '/' . $value->uri();

                $routePattern = '^' . str_replace('/', '\/', $url);

                if(strpos($value->uri(), '{')) {
                    $routePattern = '^' . preg_replace('#\{.*?\}#s', '(?:([^\/]+?))', str_replace('/', '\/', $url)) . '?$';
                }

                $this->info($routePattern);

                $endPoints[] = [
                    'url' => $routePattern,
                    'method' => strtolower($method)
                ];
            }
        }

        try {
            $conf = new RdKafka\Conf();
            //$conf->set('log_level', (string) LOG_DEBUG);
            //$conf->set('debug', 'all');
            $conf->set('acks', '-1');
            $conf->set('bootstrap.servers', 'kafka:9092');

            $rk = new RdKafka\Producer($conf);
            $rk->addBrokers("kafka:9092");

            $topicConf = new RdKafka\TopicConf();
            // $topicConf->set('auto.commit.enable', 'false');  // don't commit offset automatically
            // $topicConf->set('auto.commit.interval.ms', self::TOPIC_CONF_AUTO_COMMIT_INTERVAL);
            // $topicConf->set('offset.store.method', self::TOPIC_CONF_OFFSET_STORE_METHOD);
            // if (self::TOPIC_CONF_OFFSET_STORE_METHOD === 'file') {
            //     $topicConf->set('offset.store.path', sys_get_temp_dir());
            // }
            // // where to start consuming messages when there is no initial offset in offset store or the desired offset is out of range
            // $topicConf->set('auto.offset.reset', self::TOPIC_CONF_OFFSET_RESET);

            $topic = $rk->newTopic('service-registry', $topicConf);

            $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode([
                'topic' => 'service-registry',
                'messageType' => 'EVENT',
                'messageId' => Str::uuid()->toString(),
                'eventId' => 'SERVICE_ONLINE',
                'serviceId' => 'simple-trader-api',
                'instanceId' => $instanceId,
                'supportedCommunicationChannels' => ['rest'],
                'hostname' => 'api-proxy',
                'port' => 8001,
                'endpoints' => $endPoints
            ]));

            $rk->flush(1000);

            $this->info('Added service to registry (' . count($endPoints) . ' routes)!');

        }
        catch(Exception $ex)
        {
            $this->error($ex);
        }

        return Command::SUCCESS;
    }
}
