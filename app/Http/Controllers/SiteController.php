<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiteController extends Controller
{

    
    public function viewHome(): View
    {
        $data = Currency::getRates("ZAR");
        $currencies = Currency::extractCurrencyKeys($data);
        Log::info("Returning home view", $data);
        return view('home', ['data' => (object) $data, 'currencies' => $currencies]);
    }

    public function getLatestRates(Request $request)
    {
        $data = Currency::getRates('ZAR');
        return response()->json($data);
    }

    public function convertCurrency(Request $request)
{
    Log::info($request);
    $amount = $request->input('amount');
    $fromCurrency = $request->input('from_currency');
    $toCurrency = $request->input('to_currency');
    $data = Currency::getRates($fromCurrency);

    $fromToCurrency = strtoupper($fromCurrency) . '_' . strtoupper($toCurrency);
    $toFromCurrency = strtoupper($toCurrency) . '_' . strtoupper($fromCurrency);
    Log::info($toFromCurrency);
    Log::info($fromToCurrency);
    if (isset($data['forex'][$fromToCurrency])) {
        Log::info($fromCurrency);
        $rate = $data['forex'][$fromToCurrency];
        $convertedAmount = $amount * $rate;
        return response()->json(['convertedAmount' => $convertedAmount, 'rate' => $rate]);
    } elseif (isset($data['forex'][$toFromCurrency])) {
        $rate = 1 / $data['forex'][$toFromCurrency];
        $convertedAmount = $amount * $rate;
        return response()->json(['convertedAmount' => $convertedAmount, 'rate' => $rate]);
    }

    return response()->json(['error' => 'Currency pair not found'], 400);
}


}
