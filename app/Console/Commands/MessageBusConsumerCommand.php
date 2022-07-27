<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use RdKafka;

class MessageBusConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bus:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Message bus consumer command';

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
        $conf = new RdKafka\Conf();

        // Set a rebalance callback to log partition assignments (optional)
        $conf->setRebalanceCb(function (RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
            $partitionCount = count($partitions);
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    $this->info("Assign: $partitionCount\n");
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $this->info("Revoke: $partitionCount\n");
                    $kafka->assign(null);
                    break;

                default:
                    throw new \Exception($err);
            }
        });


        $conf->set('group.id', 'st-rest-api');
        $conf->set('metadata.broker.list', 'kafka:9092');
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new RdKafka\KafkaConsumer($conf);

        $consumer->subscribe([
            'users',
            'orders',
            'bot-sessions'
        ]);

        echo "Partition assignment... (may take some time when quickly re-joining the group after leaving it.)\n";

        // start daemon (never ending loop)
        while (true) {
            try {
                $messageData = $consumer->consume(100); // ten times a second

                // no error - normal response
                if($messageData->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
                    $topicName = $messageData->topic_name;
                    $key       = $messageData->key;
                    $message   = json_decode($messageData->payload);

                    $this->info('topic: ' + $topicName);
                    $this->info('message id: ' . $key);
                    $this->info('request id: ' . $message->requestId);


                    // handle the different types of messages
                    switch($topicName) {
                        /**
                         * Stream of events for account updates
                         * - balance updates
                         * - exchange account changes
                         */
                        case 'users':

                            // find user by id
                            // update balance (optional)
                            // update exchange account (optional)

                            break;
                        
                        /**
                         * Stream of events for orders
                         * - order updates
                         */
                        case 'orders':

                            // find order by id
                            // update order record
                            // update order fill records (optional)

                            break;

                        /**
                         * Stream of events for bot sessions
                         * - bot session updates
                         */
                        case 'bot_sessions':

                            // find bot session by id
                            // update bot session record

                            break;
                    }
                }
            }
            catch (Exception $ex) {
                $this->error($ex);
            }
        }
    }
}
