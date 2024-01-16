<?php
use Microwave\Baltbit\Config;
use Microwave\Baltbit\External\BinCurrencyConvert;
use Microwave\Baltbit\External\EthAnalyzer;
use PHPUnit\Framework\TestCase;

class EthAnalyzerTest extends TestCase
{
    public function testFetchTransactions()
    {
        $configMock = $this->createMock(Config::class);
        $configMock->method('get')->willReturn('300');

        $binCurrencyConvertMock = $this->createMock(BinCurrencyConvert::class);

        $ethAnalyzer = $this->getMockBuilder(EthAnalyzer::class)
            ->setConstructorArgs([$configMock, $binCurrencyConvertMock])
            ->onlyMethods(['request'])
            ->getMock();

        $ethAnalyzer->expects($this->at(0))
            ->method('request')
            ->willReturn(['result' => '0x123456']); // For requestBlockNumber

        $ethAnalyzer->expects($this->at(1))
            ->method('request')
            ->willReturn(['result' => ['transaction1', 'transaction2']]); // For fetchTransactions

        $result = $ethAnalyzer->fetchTransactions(600);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testFetchTransactionByAmountAndCurrency()
    {
        $configMock = $this->createMock(Config::class);
        $configMock->method('get')->willReturn('300');

        $binCurrencyConvertMock = $this->createMock(BinCurrencyConvert::class);
        $binCurrencyConvertMock->method('convert')->willReturn(100.00);

        $ethAnalyzer = $this->getMockBuilder(EthAnalyzer::class)
            ->setConstructorArgs([$configMock, $binCurrencyConvertMock])
            ->onlyMethods(['fetchTransactions'])
            ->getMock();

        $ethAnalyzer->expects($this->once())
            ->method('fetchTransactions')
            ->willReturn([
                ['data' => '0x123456789'],
                ['data' => '0x987654321'],
            ]);

        $result = $ethAnalyzer->fetchTransactionByAmountAndCurrency(50, 'USD');

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }
}
