<?php

namespace App;

use App\Components\NameRandomizer;
use RdKafka\Producer;
use RdKafka\ProducerTopic;

class MessageProducer
{
    use NameRandomizer;

    protected Producer $producer;
    protected MessageConfig $config;
    protected ProducerTopic $topic;

    public function __construct(MessageConfig $config)
    {
        $this->producer = new Producer($config->getConfig());
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $this->producer->newTopic($topic);
        return $this;
    }

    public function sendPayload(string $payload): int
    {
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);
        $this->producer->poll(0);
        return $this->producer->flush(10000);
    }
}