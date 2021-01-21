<?php

namespace App;

use RdKafka\Consumer;
use RdKafka\ConsumerTopic;
use RdKafka\Message;

class MessageConsumer
{
    protected Consumer $consumer;
    protected ConsumerTopic $topic;
    protected MessageTopicConfig $topicConfig;

    public function __construct(MessageConfig $config, MessageTopicConfig $topicConfig)
    {
        $this->consumer = new Consumer($config->getConfig());
        $this->topicConfig = $topicConfig;
    }

    public function addBrokers(string $brokerList): MessageConsumer
    {
        $this->consumer->addBrokers($brokerList);
        return $this;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $this->consumer->newTopic($topic, $this->topicConfig->getConfig());
        $this->topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);
        return $this;
    }

    public function consume(int $timeout = 100): ?Message
    {
        return $this->topic->consume(0, $timeout);
    }
}