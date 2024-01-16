<?php

use Microwave\Baltbit\Config;
use Microwave\Baltbit\External\BinCurrencyConvert;
use PHPUnit\Framework\TestCase;

class BinCurrencyConvertTest extends TestCase
{
    public function testConvert()
    {
        $configMock = $this->createMock(Config::class);
        $configMock->method('get')->willReturnMap([
            ['default_currency', 'USD'],
            ['api_url', 'https://example.com/api'],
            ['api_key', 'your_api_key'],
        ]);

        $binCurrencyConverter = new BinCurrencyConvert($configMock);

        $reflection = new ReflectionClass($binCurrencyConverter);
        $property = $reflection->getProperty('tariffList');
        $property->setAccessible(true);
        $property->setValue($binCurrencyConverter, ['USD' => 1.2]);

        $result = $binCurrencyConverter->convert(100, 'USD');

        $this->assertEquals(120, $result);
    }
}
