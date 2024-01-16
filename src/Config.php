<?php

namespace Microwave\Baltbit;

class Config
{
    private array $config;

    public function __construct(string $forClass)
    {
        $configPath = ROOT_PATH . 'config/app.php';

        if (!file_exists($configPath)) {
            throw new \Exception('Config not found: app.php');
        }

        $configFile = require $configPath;

        if (empty($configFile[$forClass])) {
            throw new \Exception('Config not found for required class');
        }

        $this->config = $configFile[$forClass];
    }

    public function get(string $key, ?string $default = null): ?string
    {
        return $this->config[$key] ?? $default;
    }
}
