<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Retail Sales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <!-- Logo/Header -->
            <div class="px-6 py-4 border-b border-slate-800">
                <h1 class="text-xl font-bold leading-tight">Data Warehouse</h1>
                <p class="text-sm text-slate-400 leading-tight">Stationery Store</p>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('sales_fact_table') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Sales Fact Table</span>
                </a>

                <a href="{{ route('sales_fact_table') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Inventory Fact Table</span>
                </a>

                <a href="{{ route('sales_fact_table') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Promotion</span>
                </a>

                <a href="{{ route('sales_fact_table') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Snap Shot</span>
                </a>
            </nav>

            <!-- Footer -->
            <div class="px-4 py-3 border-t border-slate-800">
                <p class="text-xs text-slate-400">© 2025 Stationery Store</p>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
                <p class="text-sm text-gray-600 mt-1">Ringkasan Data Penjualan Stationery Store</p>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mb-6">
                    <!-- Total Records -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-blue-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Records</h3>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($totalRecords, 0, ',', '.') }}</div>
                        <p class="text-xs text-gray-500 mt-1">Total transaksi</p>
                    </div>
                    
                    <!-- Total Gross Profit -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-green-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Gross Profit</h3>
                        <div class="text-3xl font-bold text-gray-900 font-mono">Rp {{ number_format($totalGrossProfit, 0, ',', '.') }}</div>
                        <p class="text-xs text-gray-500 mt-1">Keseluruhan profit</p>
                    </div>
                    
                    <!-- Rata-rata Gross Profit -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-yellow-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Rata-rata Gross Profit</h3>
                        <div class="text-3xl font-bold text-gray-900 font-mono">Rp {{ number_format($avgGrossProfit, 0, ',', '.') }}</div>
                        <p class="text-xs text-gray-500 mt-1">Per transaksi</p>
                    </div>
                    
                </div>
               
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Chart</h2>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
