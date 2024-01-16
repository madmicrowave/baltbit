<?php

namespace Microwave\Baltbit;

use Microwave\Baltbit\External\BinCurrencyConvert;
use Microwave\Baltbit\External\EthAnalyzer;

class WebScript
{
    public function run(): void
    {
        header('Content-Type: application/json');

        try {
            http_response_code(200);

            $currencyConvertConfig = new Config(BinCurrencyConvert::class);

            $eth = new EthAnalyzer(
                new Config(EthAnalyzer::class),
                new BinCurrencyConvert(
                    $currencyConvertConfig
                )
            );

            $amount = (float) ($_GET['amount'] ?? 10);
            $currency = (string) ($_GET['currency'] ?? $currencyConvertConfig->get('default_currency'));

            die(
                $this->handleResponse(
                    $eth->fetchTransactionByAmountAndCurrency($amount, $currency)
                )
            );
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            die(
                $this->handleResponse([
                    'error' => true,
                    'message' => $e->getMessage(),
                ])
            );
        }
    }

    private function handleResponse(array $response): string
    {
        return json_encode($response, JSON_THROW_ON_ERROR);
    }
}
