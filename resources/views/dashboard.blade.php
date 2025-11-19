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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

                    <!-- Total Records -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-blue-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Records</h3>
                        <div class="text-3xl font-bold text-gray-900">
                            {{ number_format($totalRecords, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Total transaksi</p>
                    </div>

                    <!-- Total Gross Profit -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-green-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Total Gross Profit</h3>
                        <div class="text-3xl font-bold text-gray-900 font-mono">
                            Rp {{ number_format($totalGrossProfit, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Keseluruhan profit</p>
                    </div>

                    <!-- Rata-rata Gross Profit -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 border-l-8 border-l-yellow-500 w-[350px]">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Rata-rata Gross Profit</h3>
                        <div class="text-3xl font-bold text-gray-900 font-mono">
                            Rp {{ number_format($avgGrossProfit, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Per transaksi</p>
                    </div>

                </div>

                <!-- Filter Form dengan Date Picker -->
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Chart</h2>
                <form method="GET" action="{{ route('dashboard') }}" class="space-y-4 bg-white p-4 rounded-xl shadow-sm mb-6">

                    <div class="flex gap-4">

                        <!-- Product Dropdown -->
                        <div class="flex flex-col w-1/3">
                            <label class="text-sm text-gray-600 mb-1">Produk</label>
                            <select name="product_key" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Produk</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->product_key }}"
                                        {{ request('product_key') == $p->product_key ? 'selected' : '' }}>
                                        {{ $p->product_description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Start Date Picker -->
                        <div class="flex flex-col w-1/3">
                            <label class="text-sm text-gray-600 mb-1">Tanggal Mulai</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                value="{{ request('start_date') }}"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                        <!-- End Date Picker -->
                        <div class="flex flex-col w-1/3">
                            <label class="text-sm text-gray-600 mb-1">Tanggal Selesai</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                value="{{ request('end_date') }}"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                        </div>

                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors font-medium">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 transition-colors font-medium">
                            Reset Filter
                        </a>
                    </div>
                </form>

                <!-- Chart Container -->
                @if(isset($chartData) && !empty($chartData))
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Gross Profit per Store</h3>
                    <div class="chart-wrapper">
                        <canvas id="grossProfitChart"></canvas>
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
    let myChart = null;

    // Data awal dari server
    const initialData = @json($chartData ?? null);

    // Inisialisasi chart
    function initChart(data) {
        if (!data || !data.labels || !data.values) {
            console.log('No chart data available');
            return;
        }

        const canvas = document.getElementById('grossProfitChart');
        if (!canvas) {
            console.log('Canvas element not found');
            return;
        }

        const ctx = canvas.getContext('2d');
        
        // Destroy chart sebelumnya jika ada
        if (myChart) {
            myChart.destroy();
        }
        
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Gross Profit per Store',
                    data: data.values,
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                const index = context[0].dataIndex;
                                return data.store_names && data.store_names[index] 
                                    ? data.store_names[index] 
                                    : 'Store ' + data.labels[index];
                            },
                            label: function(context) {
                                return 'Gross Profit: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Store ID'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Gross Profit (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Load chart setelah DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        if (initialData) {
            initChart(initialData);
        }
    });
    </script>

    <style>
    .chart-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .chart-wrapper {
        height: 400px;
        margin-top: 20px;
        position: relative;
    }

    /* Styling untuk date picker */
    input[type="date"] {
        cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        padding: 5px;
    }

    input[type="date"]:hover {
        border-color: #3b82f6;
    }
    </style>
</body>
</html>