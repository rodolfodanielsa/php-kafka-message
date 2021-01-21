<?php

require_once "vendor/autoload.php";

use App\MessageConfig;
use App\MessageConsumer;
use App\MessageProducer;
use App\MessageTopicConfig;

const BROKER_LIST = 'kafka:9092';
const CONSUMER_GROUP = 'secondGroup';
const TIMEOUT = 50;
const TOPIC_PRODUCE = "message";
const TOPIC_CONSUME = "topicB";

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

$confProducer = new MessageConfig();
$confProducer->setConfigs($defaultProducerConfigs);

$confConsumer = new MessageConfig();

$confConsumer->setConfigs($defaultConsumerConfigs);

$topicConf = new MessageTopicConfig();
$topicConf->setConfigs($defaultTopicConfigs);

$producer = new MessageProducer($confProducer);
$result = $producer->setTopic(TOPIC_PRODUCE)->sendPayload($producer->returnFirstMessage());

if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
    throw new \RuntimeException('Was unable to flush, messages might be lost!');
}

$consumer = new MessageConsumer($confConsumer, $topicConf);
$consumer->addBrokers("kafka")->setTopic(TOPIC_CONSUME);
$count = 0;
$finalMessage = 'no response';
while ($count < 1000) {

    $message = $consumer->consume(TIMEOUT);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            if ($message->payload) {
                $finalMessage = $message->payload;
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

    $count += TIMEOUT;
}

echo $finalMessage. "\n";