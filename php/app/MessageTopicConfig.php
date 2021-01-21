<?php

namespace App;

use RdKafka\Conf;
use RdKafka\TopicConf;

class MessageTopicConfig
{
    protected TopicConf $config;

    public function __construct()
    {
        $this->config = new TopicConf();
    }

    public function setConfigs(array $configs): void
    {
        foreach ($configs as $key => $value) {
            $this->config->set($key, $value);
        }
    }

    public function getConfig(): TopicConf
    {
        return $this->config;
    }
}