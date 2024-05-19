<?php

namespace App\Models;

use App\Services\CompleteApiService;
use Illuminate\Support\Facades\Http;

class Currency {

    static function getRates ($baseCurrency) {
        $caller = env("USE_API");
        if ($caller != 0) {
            $completeApiService = new CompleteApiService();
            $data = $completeApiService->getCurrencies($baseCurrency);
        } else {
            $response = Http::get("https://www.completeapi.com/free_currencies.min.json");
            $data = $response->json();
        }

        return $data;
    }

    static function extractCurrencyKeys($forexData)
    {   
        
        if (isset($forexData['forex'])) {
            $keys = array_keys($forexData['forex']);
            $currencies = [];

            foreach ($keys as $key) {
                $parts = explode('_', $key);
                foreach ($parts as $part) {
                    if (!in_array($part, $currencies)) {
                        $currencies[] = $part;
                    }
                }
            }

            return $currencies;
        }

        return [];
    }
}