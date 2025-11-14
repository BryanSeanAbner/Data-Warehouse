<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');

Route::get('/sales_fact_table', [AnalyticsController::class, 'sales_fact_table'])->name('sales_fact_table');