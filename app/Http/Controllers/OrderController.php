<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateFormatRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function validateFormat(ValidateFormatRequest $request)
    {
        $result = $this->orderService->validateFormat($request->validated());

        return response()->json($result);
    }
}
