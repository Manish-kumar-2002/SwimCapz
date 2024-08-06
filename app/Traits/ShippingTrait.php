<?php

namespace App\Traits;

use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

trait ShippingTrait
{
    // public function setCurrencyExchangeRate()
    // {
    //     // $url = 'https://alpha-vantage.p.rapidapi.com/query';
    //     $url = 'https://www.xe.com/api/protected/midmarket-converter/';
    //     $queryParameters = [
    //         // 'to_currency' => $toCurrency,
    //         'function' => 'CURRENCY_EXCHANGE_RATE',
    //         // 'from_currency' => $fromCurrency
    //     ];

    //     $client = new Client([
    //         'headers' => [
    //             'X-RapidAPI-Host' => 'alpha-vantage.p.rapidapi.com',
    //             'X-RapidAPI-Key' => $this->alphaVantageApiKey,
    //         ]
    //     ]);
    //     try {
    //         $response = $client->get($url, ['query' => $queryParameters]);

    //         $data = json_decode($response->getBody(), true);
    //         // Accessing the exchange rate
    //         if (isset($data['Realtime Currency Exchange Rate'])) {
    //             $exchangeRateData = $data['Realtime Currency Exchange Rate'];
    //             if (isset($exchangeRateData['5. Exchange Rate'])) {
    //                 return $exchangeRateData['5. Exchange Rate'];
    //             } else {
    //                 // Handle case when the exchange rate key is not found
    //                 return null;
    //             }
    //         } else {
    //             // Handle case when the main key is not found
    //             return null;
    //         }
    //     } catch (\Exception $e) {
    //         // Log or handle any errors that occur during the API request
    //         \Log::error('Error fetching currency exchange rate: ' . $e->getMessage());
    //         return null;
    //     }
    // }

    public function setCurrencyExchangeRate()
    {
        $url = 'https://www.xe.com/api/protected/midmarket-converter/';

        $headers = [
            'sec-ch-ua: "Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
            'sec-ch-ua-mobile: ?0',
            'authorization: Basic bG9kZXN0YXI6cHVnc25heA==',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'sec-ch-ua-platform: "Linux"',
            'Accept: */*',
            'Sec-Fetch-Site: same-origin',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Dest: empty',
            'host: www.xe.com',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        try {
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                $data = json_decode($response, true);
                // Accessing the exchange rate
                if (isset($data['rates'])) {

                    // Assuming you have a Currency model and a corresponding table
                    foreach ($data['rates'] as $currencyCode => $exchangeRate) {

                        $isExist = Currency::where('name', $currencyCode)->exists();

                        if ($isExist) {

                            Currency::where('name', $currencyCode)->update(['name' => $currencyCode, 'value' => $exchangeRate]);
                        }
                    }
                }
            } else {
                throw new \Exception('HTTP request failed with status code: ' . $httpCode);
            }
        } catch (\Exception $e) {
            // Log or handle any errors that occur during the API request
            \Log::error('Error fetching currency exchange rate: ' . $e->getMessage());
            return null;
        } finally {
            curl_close($ch);
        }
    }
}
