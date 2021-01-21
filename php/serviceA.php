<?php

require_once "vendor/autoload.php";

use App\MessageConfig;
use App\MessageConsumer;
use App\MessageProducer;
use App\MessageTopicConfig;

const BROKER_LIST = 'kafka:9092';
const CONSUMER_GROUP = 'firstGroup';
const TIMEOUT = 120000;
const TOPIC_PRODUCE = "topicA";
const TOPIC_CONSUME = "message";

$defaultProducerConfigs = ['metadata.broker.list' => BROKER_LIST];
$defaultConsumerConfigs = [
    'metadata.broker.list' => BROKER_LIST,
    'group.id' => CONSUMER_GROUP,
];
$defaultTopicConfigs = [
    'auto.commit.interval.ms' => 10,
    'offset.store.method' => 'broker',
    'auto.offset.reset' => 'earliest',
];

$confConsumer = new MessageConfig();
$confConsumer->setConfigs($defaultConsumerConfigs);

$topicConf = new MessageTopicConfig();
$topicConf->setConfigs($defaultTopicConfigs);

$confProducer = new MessageConfig();
$confProducer->setConfigs($defaultProducerConfigs);

$consumer = new MessageConsumer($confConsumer, $topicConf);
$consumer->addBrokers("kafka")->setTopic(TOPIC_CONSUME);

$producer = new MessageProducer($confProducer);

while (true) {
    $message = $consumer->consume(TIMEOUT);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            if ($message->payload) {
                $result = $producer->setTopic(TOPIC_PRODUCE)
                    ->sendPayload($producer->returnRandomName($message->payload));
                if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                    break;
                }
            }
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}
