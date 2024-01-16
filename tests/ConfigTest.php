<?php

define('ROOT_PATH', __DIR__ . '/../');

use Microwave\Baltbit\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigForValidClass()
    {
        $config = new Config(\Microwave\Baltbit\External\EthAnalyzer::class);

        $this->assertNotNull($config->get('api_url'));
    }

    public function testConfigForInvalidClass()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Config not found for required class');

        $config = new Config('NonExistentClass');

        // No assertions are needed here since an exception is expected
    }
}
