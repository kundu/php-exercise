<?php

namespace App\Services;

use App\Constants\ApiEndPointConstants;
use Exception;
use Illuminate\Support\Facades\Http;

class FinanceRapidApiService
{
    public $authHeaders;

    public function __construct() {
        $this->authHeaders = [
            "X-RapidAPI-Key" => config('rapidapi.finance.api_key'),
            "X-RapidAPI-Host" => config('rapidapi.finance.api_host')
        ];
    }

    /**
     * get historical data from api
     *
     * @param string $symbol
     * @return array
     * @throws Exception
     */
    public function getHistoricalData(string $symbol) : array {
        $response = Http::withHeaders(
            $this->authHeaders
        )->get($this->generateApiUrl(ApiEndPointConstants::RAPID_FINANCE_HISTORICAL_DATA_END_POINT), [
            "symbol" => $symbol
        ]);
        if ($response->successful()) {
            return json_decode($response->body(), true);
        }
        throw new Exception($response->body());
    }

    /**
     * generate the api url
     *
     * @param string $endPoint
     * @return string
     */
    private function generateApiUrl(string $endPoint) : string {
        return config('rapidapi.finance.api_base_url') . $endPoint;
    }


}
