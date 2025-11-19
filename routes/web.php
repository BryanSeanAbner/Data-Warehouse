<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HadoopEtlController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

Route::get('/sales_fact_table', [DashboardController::class, 'sales_fact_table'])->name('sales_fact_table');

// Hadoop ETL Routes
Route::prefix('hadoop')->name('hadoop.')->group(function () {
    Route::get('/', [HadoopEtlController::class, 'index'])->name('index');
    Route::post('/upload', [HadoopEtlController::class, 'uploadCsv'])->name('upload');
    Route::post('/import', [HadoopEtlController::class, 'importTsv'])->name('import');
    Route::post('/export', [HadoopEtlController::class, 'exportToHadoop'])->name('export');
    Route::delete('/delete', [HadoopEtlController::class, 'deleteFile'])->name('delete');
});