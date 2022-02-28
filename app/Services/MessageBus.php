<?php
namespace App\Services;

use Exception;
use RdKafka;

class MessageBus
{
    /**
     * Produce a message onto kafka
     *
     * @param string $topic
     * @param array $data
     * @return void
     */
    public function sendMessage(string $topic, array $data): bool
    {
        try {
            $conf = new RdKafka\Conf();
            //$conf->set('log_level', (string) LOG_DEBUG);
            //$conf->set('debug', 'all');
            $conf->set('acks', '1');
            $conf->set('bootstrap.servers', 'kafka:9092');

            $producer = new RdKafka\Producer($conf);
            $producer->addBrokers("kafka:9092");

            $topicConf = new RdKafka\TopicConf();
            // $topicConf->set('auto.commit.enable', 'false');  // don't commit offset automatically
            // $topicConf->set('auto.commit.interval.ms', self::TOPIC_CONF_AUTO_COMMIT_INTERVAL);
            // $topicConf->set('offset.store.method', self::TOPIC_CONF_OFFSET_STORE_METHOD);
            // if (self::TOPIC_CONF_OFFSET_STORE_METHOD === 'file') {
            //     $topicConf->set('offset.store.path', sys_get_temp_dir());
            // }
            // // where to start consuming messages when there is no initial offset in offset store or the desired offset is out of range
            // $topicConf->set('auto.offset.reset', self::TOPIC_CONF_OFFSET_RESET);

            $topic = $producer->newTopic($topic, $topicConf);

            $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data));
            $producer->flush(100);

            return true;
        }
        catch(Exception $ex) {
            return false;
        }
    }
}