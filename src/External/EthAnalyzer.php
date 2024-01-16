<?php

namespace Microwave\Baltbit\External;

use Microwave\Baltbit\Config;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Web3;

class EthAnalyzer
{
    private Config $config;

    private BinCurrencyConvert $binanceStockCurrency;

    public function __construct(Config $config, BinCurrencyConvert $binanceStockCurrency)
    {
        $this->config = $config;
        $this->binanceStockCurrency = $binanceStockCurrency;
    }

    private function fetchTransactionsInBlocks(int $startBlock, int $endBlock): array
    {
        $transactions = $this->request([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_getLogs',
            'params' => [
                [
                    'address' => null,
                    'fromBlock' => '0x' . dechex($startBlock),
                    'toBlock' => '0x' . dechex($endBlock),
                ],
            ],
        ]);

        return $transactions['result'] ?? [];
    }

    private function requestBlockNumber(): int
    {
        $result = $this->request([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'eth_blockNumber',
            'params' => [],
        ]);

        return hexdec($result['result']) ?? 0;
    }

    public function fetchTransactions(int $secondsAgo): array
    {
        $maxPeriodTime = $this->config->get('max_period_time');
        $latestBlock = $this->requestBlockNumber();

        $loops = ceil($secondsAgo / $maxPeriodTime);

        $transactions = [];
        for ($i = 0; $i < $loops; $i++) {
            $startBlock = $latestBlock - min($maxPeriodTime, $secondsAgo) / 15;

            // Fetch transactions for the current loop
            $loopTransactions = $this->fetchTransactionsInBlocks($startBlock, $latestBlock);

            // Merge the transactions into the result array
            $transactions = array_merge($transactions, $loopTransactions);

            // Update $latestBlock for the next iteration
            $latestBlock -= min($maxPeriodTime, $secondsAgo) / 15;

            // Subtract the processed time from the remaining secondsAgo
            $secondsAgo -= min($maxPeriodTime, $secondsAgo);
        }

        return $transactions;
    }

    public function fetchTransactionByAmountAndCurrency(float $euro, string $currency): array
    {
        $transactions = $this->fetchTransactions($this->config->get('for_period_in_seconds'));

        if (empty($transactions)) {
            throw new \Exception('Transactions not found!');
        }

        $result = [];
        foreach ($transactions as $transaction) {
            $amountInWei = hexdec($transaction['data']);
            $amountInETH = $amountInWei / 1e18;

            $amountInCurrency = $this->binanceStockCurrency->convert($amountInETH, $currency);

            if ($this->inRange($amountInCurrency, $euro, $this->config->get('bound_percent'))) {
                $result[] = $transaction + ['to_currency' => $currency, 'calculated_currency' => $amountInCurrency, 'calculated_eth' => $amountInETH];
            }
        }

        return $result;
    }

    private function inRange(float $value, float $target, int $percentage): bool
    {
        $lowerBound = $target - ($target * $percentage / 100);
        $upperBound = $target + ($target * $percentage / 100);

        return ($value >= $lowerBound && $value <= $upperBound);
    }

    /**
     * @param array $queryParams
     * @return array|null
     * @throws ExternalApiException
     */
    public function request(array $request): ?array
    {
        $options = [
            CURLOPT_URL => $this->config->get('api_url') . $this->config->get('api_key'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($request, JSON_THROW_ON_ERROR),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR) ?? [];
    }
}
