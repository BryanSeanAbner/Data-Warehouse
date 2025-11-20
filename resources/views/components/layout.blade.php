<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Data Warehouse' }} - Retail Sales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style> @import 'css/style.css'; </style>
    @if(isset($includeChartJs) && $includeChartJs)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    @endif
</head>
<body class="bg-gray-50 font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar – Lebih elegan -->
    <aside class="w-64 sidebar text-white flex flex-col shadow-2xl">
        <div class="px-8 py-8 text-center border-b border-slate-700">
            <h1 class="text-2xl font-bold tracking-tight">Data Warehouse</h1>
            <p class="text-sm text-indigo-300 mt-1">Toko Feng Mulyono</p>
        </div>

        <nav class="flex-1 px-2 py-6 space-y-2">
            @php $current = request()->route()->getName(); @endphp

            <a href="{{ route('dashboard') }}" 
               class="{{ $current == 'dashboard' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('date_dimension_table') }}" 
               class="{{ $current == 'date_dimension_table' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-calendar-alt w-5"></i>
                <span>Date Dimension Table</span>
            </a>

            <a href="{{ route('accumulation_inventory') }}" 
               class="{{ $current == 'accumulation_inventory' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-boxes-stacked w-5"></i>
                <span>Accumulation Inventory</span>
            </a>

            <a href="{{ route('promotion') }}" 
               class="{{ $current == 'promotion' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-tags w-5"></i>
                <span>Promotion</span>
            </a>

            <a href="{{ route('snapshot') }}" 
               class="{{ $current == 'snapshot' ? 'bg-indigo-600 shadow-lg' : 'hover:bg-slate-700' }} flex items-center gap-3 px-5 py-3 rounded-xl transition-all duration-300">
                <i class="fas fa-camera w-5"></i>
                <span>Snap Shot</span>
            </a>
        </nav>

        <div class="px-6 py-5 border-t border-slate-700 text-center">
            <p class="text-xs text-slate-400">© 2025 Toko Feng Mulyono</p>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white/90 backdrop-blur-sm shadow-md px-8 py-6 border-b border-gray-100">
            <h2 class="text-3xl font-bold text-gray-800">{{ $headerTitle ?? 'Dashboard' }}</h2>
            <p class="text-gray-600">{{ $headerDescription ?? 'Ringkasan Data Penjualan Toko Feng Mulyono' }}</p>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            {{ $slot }}
        </main>
    </div>
</div>

@isset($scripts)
    {{ $scripts }}
@endisset

</body>
</html>

