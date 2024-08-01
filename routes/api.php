<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'validateFormat'])->name('orders.validateFormat');
