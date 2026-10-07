<?php

namespace App\Http\Controllers;

use App\Services\BusinessContextBuilder;

class BusinessContextController extends Controller
{
    public function index(BusinessContextBuilder $contextBuilder)
    {
        $context = $contextBuilder->build();

        return response()->json([
            'success' => true,
            'business_context' => $context,
        ]);
    }
}
