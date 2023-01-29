<?php

namespace App\Jobs;

use App\Services\CompanyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompanyDetailsEmailSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $symbol;
    public $startDate;
    public $endDate;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $symbol, string $startDate, string $endDate)
    {
        $this->email = $email;
        $this->symbol = $symbol;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CompanyService $companyService)
    {
        $companyService->companyDetailsEmailSend($this->email,$this->symbol,$this->startDate,$this->endDate);
    }
}
