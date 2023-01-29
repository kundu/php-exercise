<?php

namespace App\Rules;

use App\Services\CompanyService;
use Illuminate\Contracts\Validation\Rule;

class CompanySymbolExitsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $companyService = new CompanyService();
        $company = $companyService->getCompanyDetailsBySymbol($value); 
        if ($company) {
           return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Company symbol is not valid';
    }
}
