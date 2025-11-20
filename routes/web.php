<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DateDimensionController;
use App\Http\Controllers\AccumulationInventory;
use App\Http\Controllers\Promotion;
use App\Http\Controllers\Snapshot;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/date-dimension', [DateDimensionController::class, 'index'])->name('date_dimension_table');

Route::get('/accumulation-inventory', [AccumulationInventory::class, 'index'])->name('accumulation_inventory');

Route::get('/promotion', [Promotion::class, 'index'])->name('promotion');

Route::get('/snapshot', [Snapshot::class, 'index'])->name('snapshot');