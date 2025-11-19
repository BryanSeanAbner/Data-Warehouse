<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Retail Sales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
        }
        body { background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%); }
        .sidebar { background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(99,102,241,0.3); }
        .chart-wrapper { height: 420px; position: relative; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar – Lebih elegan -->
    <aside class="w-64 sidebar text-white flex flex-col shadow-2xl">
        <div class="px-8 py-8 text-center border-b border-slate-700">
            <h1 class="text-2xl font-bold tracking-tight">Data Warehouse</h1>
            <p class="text-sm text-indigo-300 mt-1">Stationery Store</p>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            @php $current = request()->route()->getName(); @endphp

            <a href="{{ route('dashboard') }}" 
               class="{{ $current == 'dashboard' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('sales_fact_table') }}" 
               class="flex items-center gap-3 px-5 py-3 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <i class="fas fa-shopping-cart w-5"></i>
                <span>Sales Fact Table</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-5 py-3 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <i class="fas fa-boxes-stacked w-5"></i>
                <span>Inventory Fact Table</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-5 py-3 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <i class="fas fa-tags w-5"></i>
                <span>Promotion</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-5 py-3 rounded-xl hover:bg-slate-700 transition-all duration-300">
                <i class="fas fa-camera w-5"></i>
                <span>Snap Shot</span>
            </a>
        </nav>

        <div class="px-6 py-5 border-t border-slate-700 text-center">
            <p class="text-xs text-slate-400">© 2025 Stationery Store</p>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white/90 backdrop-blur-sm shadow-md px-8 py-6 border-b border-gray-100">
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-600">Ringkasan Data Penjualan Stationery Store</p>
        </header>

        <main class="flex-1 overflow-y-auto p-8">

            <!-- Stats Cards – Lebih menarik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover border-l-8 border-l-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Records</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-2 font-mono">
                                {{ number_format($totalRecords, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">Total transaksi</p>
                        </div>
                        <i class="fas fa-file-invoice-dollar text-6xl text-blue-500 opacity-20"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover border-l-8 border-l-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Gross Profit</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-2 font-mono">
                                Rp {{ number_format($totalGrossProfit, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">Keseluruhan profit</p>
                        </div>
                        <i class="fas fa-sack-dollar text-6xl text-green-500 opacity-20"></i>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover border-l-8 border-l-amber-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Rata-rata Gross Profit</p>
                            <p class="text-3xl font-extrabold text-gray-800 mt-2 font-mono">
                                Rp {{ number_format($avgGrossProfit, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2">Per transaksi</p>
                        </div>
                        <i class="fas fa-arrow-trend-up text-6xl text-amber-500 opacity-20"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Form – Lebih bersih -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-chart-bar text-indigo-600"></i> Chart Gross Profit per Store
                </h2>

                <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                        <select name="product_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                            <option value="">Semua Produk</option>
                            @foreach($products as $p)
                                <option value="{{ $p->product_key }}" {{ request('product_key') == $p->product_key ? 'selected' : '' }}>
                                    {{ $p->product_description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500">
                    </div>

                    <div class="flex items-end gap-3">
                        <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold shadow-lg flex items-center gap-2">
                            <i class="fas fa-filter"></i> Terapkan
                        </button>
                        <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Chart -->
            @if(isset($chartData) && !empty($chartData))
            <div class="bg-white rounded-2xl shadow-lg p-8">
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
    const initialData = @json($chartData ?? null);

    function initChart(data) {
        if (!data || !data.labels || !data.values) return;

        const ctx = document.getElementById('grossProfitChart').getContext('2d');
        if (myChart) myChart.destroy();

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.7)');
        gradient.addColorStop(1, 'rgba(168, 85, 247, 0.3)');

        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Gross Profit per Store',
                    data: data.values,
                    backgroundColor: gradient,
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 10,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1800, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        callbacks: {
                            label: ctx => 'Gross Profit: Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => initialData && initChart(initialData));
</script>

</body>
</html>