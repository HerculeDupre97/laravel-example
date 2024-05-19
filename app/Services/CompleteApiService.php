<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class CompleteApiService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('COMPLETE_API_KEY');
        $this->baseUrl = 'https://www.completeapi.com/v1/' . $this->apiKey;
    }

    public function getCurrencies($baseCurrency)
    {
        try {
            $url = $this->baseUrl . '/currency/' . $baseCurrency;
            Log::info($url);
            $response = $this->client->request('GET', $url);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Log the error with relevant details
            Log::error('Error fetching currencies', [
                'base_currency' => $baseCurrency,
                'error_message' => $e->getMessage(),
                'url' => $url,
            ]);
            return ['error' => $e->getMessage()];
        }
    }

}
