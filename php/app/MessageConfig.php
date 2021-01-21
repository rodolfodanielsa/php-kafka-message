<?php

namespace App;

use RdKafka\Conf;

class MessageConfig
{
    protected Conf $config;

    public function __construct()
    {
        $this->config = new Conf();
    }

    public function setConfigs(array $configs): void
    {
        foreach ($configs as $key => $value) {
            $this->config->set($key, $value);
        }
    }

    public function getConfig(): Conf
    {
        return $this->config;
    }
}