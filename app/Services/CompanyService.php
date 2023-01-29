<?php

namespace App\Services;

use App\Mail\CompanyDetailsMail;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CompanyService
{
    /**
     * get company details from symbol.
     *
     * @param string $symbol
     * @return array|null
     */
    public function getCompanyDetailsBySymbol(string $symbol) : array|null {
        $companies = collect(json_decode(file_get_contents(public_path('json/companies.json'), true), true));
        return $companies->where('Symbol', $symbol)->first();
    }

    /**
     * get company historical details
     *
     * @param string $symbol
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection|null
     */
    public function getCompanyHistoricalDetails(string $symbol, string $startDate = null, string $endDate = null) : Collection|null {
        $financeRapidApiService = new FinanceRapidApiService();
        $response = $financeRapidApiService->getHistoricalData($symbol);
        if (sizeof($response) && sizeof($response['prices'])) {
            $prices = collect($response['prices']);
            if ($startDate && $endDate) {
                $prices = $prices->where('date', '>=', strtotime($startDate . " 00:00:00"))->where('date', '<=', strtotime($endDate . " 23:59:59"));
            }
            return $prices;
        }
        return null;
    }

    /**
     * render html of historical prices table
     *
     * @param Collection|null $prices
     * @return string
     */
    public function historicalDataTableViewRender(Collection|null $prices) : string {
        return view('company.historical-data-table', compact('prices'))->render();
    }

    /**
     * send email to user with company details
     *
     * @param string $email
     * @param string $symbol
     * @param string $startDate
     * @param string $endDate
     * @return void
     */
    public function companyDetailsEmailSend(string $email, string $symbol, string $startDate, string $endDate) : void {
        try {
            $company = $this->getCompanyDetailsBySymbol($symbol);
            $data = [
                'subject' => $company['Company Name'],
                'body' => "From $startDate to $endDate",
            ];
            Mail::to($email)->send(new CompanyDetailsMail($data));
        } catch (Exception $exception) {
            Log::error("Email can not send", [$exception]);
        }
    }
}
