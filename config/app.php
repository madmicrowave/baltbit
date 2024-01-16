<?php

return [
    \Microwave\Baltbit\External\BinCurrencyConvert::class => [
        'api_url' => 'https://api.binance.com/api/v3/ticker/price',
        'default_currency' => 'ETHEUR',
    ],
    \Microwave\Baltbit\External\EthAnalyzer::class => [
        'api_url' => 'https://mainnet.infura.io/v3/',
        'api_key' => getenv('INFURA_API_KEY'),
        'for_period_in_seconds' => 600,
        'max_period_time' => 300,
        'bound_percent' => 3,
    ]
];
