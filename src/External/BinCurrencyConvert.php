<?php

namespace Microwave\Baltbit\External;

use Microwave\Baltbit\Config;

class BinCurrencyConvert
{
    private Config $config;

    private array $tariffList = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function convert(float $amount, string $to = null): float
    {
        if (!$to) {
            $to = $this->config->get('default_currency');
        }

        if (!isset($this->tariffList[$to])) {
            $ticker = $this->request([
                'symbol' => $to,
            ]);

            if ($ticker['price'] ?? false) {
                $this->tariffList[$to] = $ticker['price'];
            }
        }

        return $amount * $this->tariffList[$to];
    }

    /**
     * @param array $queryParams
     * @return array|null
     * @throws ExternalApiException
     */
    public function request(array $queryParams): ?array
    {
        $curl = curl_init($this->config->get('api_url') . '?' . http_build_query($queryParams + ['apikey' => $this->config->get('api_key')]));

        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session and get the response
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            throw new ExternalApiException('cURL error: ' . curl_error($curl));
        }

        // Close cURL session
        curl_close($curl);

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR) ?? null;
    }
}
