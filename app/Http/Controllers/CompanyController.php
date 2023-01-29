<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoricalDataSearchRequest;
use App\Jobs\CompanyDetailsEmailSend;
use App\Services\CompanyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function index() {
        return view('company.historical-data-view');
    }

    public function historicalDataSearch(HistoricalDataSearchRequest $request, CompanyService $companyService) {
        try {
            $prices = $companyService->getCompanyHistoricalDetails($request->company_symbol, $request->start_date, $request->end_date);
            $tableView = $companyService->historicalDataTableViewRender($prices);
            $chart['openPrices'] = $prices->pluck('open')->toArray();
            $chart['closePrices'] = $prices->pluck('close')->toArray();
            $dates = $prices->pluck('date')->toArray();
            $chart['labels'] = array_map(function($value) { return date('Y-m-d', $value); }, $dates);

            CompanyDetailsEmailSend::dispatch($request->email, $request->company_symbol, $request->start_date, $request->end_date);

            return ['code' => 200, 'message' => "All historical prices", 'html' => $tableView, 'chartData' => $chart];
        } catch (Exception $exception) {
            Log::error("Exception : ", [$exception]);
            return ['code' => 500, 'message' => "Internal Server Error"];
        }
    }
}
