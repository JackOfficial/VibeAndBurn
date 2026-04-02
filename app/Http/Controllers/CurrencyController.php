<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __invoke(Request $request, CurrencyService $service)
    {
        // Add basic validation
        $request->validate([
            'from' => 'required|string|max:3',
            'amount' => 'required|numeric'
        ]);

        return $service->convert(
            $request->from, 
            'USD', 
            $request->amount
        );
    }
}
