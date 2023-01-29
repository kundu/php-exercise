<?php

namespace Tests;

use Tests\TestCase;
use App\Http\Requests\HistoricalDataSearchRequest;
use Illuminate\Support\Facades\Validator;

class HistoricalDataSearchRequestTest extends TestCase
{

    public function test_valid_request()
    {
        $data = [
            'company_symbol' => 'AAPL',
            'start_date' => '2020-01-01',
            'end_date' => '2020-01-31',
            'email' => 'example@gmail.com'
        ];
        $request = new HistoricalDataSearchRequest();
        $request->replace($data);
        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_invalid_request()
    {
        $data = [
            'company_symbol' => '',
            'start_date' => '2020-01-31',
            'end_date' => '2020-01-01',
            'email' => 'invalid_email'
        ];

        $request = new HistoricalDataSearchRequest();
        $request->replace($data);
        $validator = Validator::make($request->all(), $request->rules());
        $this->assertTrue($validator->fails());
    }
}
