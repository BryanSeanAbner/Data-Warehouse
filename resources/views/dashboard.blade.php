<x-layout :includeChartJs="true">

    <!-- Stats Cards -->
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

    <!-- Filter Form Chart Gross Profit per Store -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <i class="fas fa-chart-bar text-indigo-600"></i> Chart Gross Profit per Store
        </h2>

        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                <select name="gross_product_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Semua Produk</option>
                    @foreach($products as $p)
                        <option value="{{ $p->product_key }}" {{ request('gross_product_key') == $p->product_key ? 'selected' : '' }}>
                            {{ $p->product_description }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="gross_start_date" value="{{ request('gross_start_date') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="gross_end_date" value="{{ request('gross_end_date') }}"
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
            <input type="hidden" name="inventory_product_key" value="{{ request('inventory_product_key') }}">
            <input type="hidden" name="inventory_start_date" value="{{ request('inventory_start_date') }}">
            <input type="hidden" name="inventory_end_date" value="{{ request('inventory_end_date') }}">
            <input type="hidden" name="inventory_store_key" value="{{ request('inventory_store_key') }}">
        </form>
    </div>

    <!-- Chart Gross Profit -->
    @if(request('gross_product_key') || request('gross_start_date') || request('gross_end_date'))
        @if(isset($chartData) && !empty($chartData) && !empty($chartData['values']))
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="chart-wrapper">
                    <canvas id="grossProfitChart"></canvas>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-5xl text-slate-300 mb-2"></i>
                    <p class="text-slate-500 text-base">Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
                </div>
            </div>
        @endif
    @else
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-indigo-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Pilih Filter untuk Melihat Chart Gross Profit</h3>
                <p class="text-slate-500">Silakan pilih produk atau rentang tanggal dari filter di atas untuk menampilkan analisis gross profit per store.</p>
            </div>
        </div>
    @endif

    <!-- Filter Form Chart Quantity On Hand -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
            <i class="fas fa-chart-bar text-indigo-600"></i> Chart Quantity On Hand
        </h2>

        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Produk</label>
                <select name="inventory_product_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Semua Produk</option>
                    @foreach($products as $p)
                        <option value="{{ $p->product_key }}" {{ request('inventory_product_key') == $p->product_key ? 'selected' : '' }}>
                            {{ $p->product_description }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Store</label>
                <select name="inventory_store_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Semua Store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->store_key }}" {{ request('inventory_store_key') == $store->store_key ? 'selected' : '' }}>
                            {{ $store->store_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="inventory_start_date" value="{{ request('inventory_start_date') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="inventory_end_date" value="{{ request('inventory_end_date') }}"
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
            <input type="hidden" name="gross_product_key" value="{{ request('gross_product_key') }}">
            <input type="hidden" name="gross_start_date" value="{{ request('gross_start_date') }}">
            <input type="hidden" name="gross_end_date" value="{{ request('gross_end_date') }}">
        </form>
    </div>

    <!-- Chart Quantity Awal & Akhir Hari -->
    @if(request('inventory_product_key') || request('inventory_store_key') || request('inventory_start_date') || request('inventory_end_date'))
        @if((isset($chartAwal) && !empty($chartAwal) && !empty($chartAwal['values'])) || (isset($chartAkhir) && !empty($chartAkhir) && !empty($chartAkhir['values'])))
            <div class="flex gap-6">
                @if(isset($chartAwal) && !empty($chartAwal) && !empty($chartAwal['values']))
                    <div class="bg-white rounded-2xl shadow-lg p-8 w-1/2">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-3">Quantity Awal Hari</h2>
                        <div class="chart-wrapper">
                            <canvas id="chartAwal"></canvas>
                        </div>
                    </div>
                @endif

                @if(isset($chartAkhir) && !empty($chartAkhir) && !empty($chartAkhir['values']))
                    <div class="bg-white rounded-2xl shadow-lg p-8 w-1/2">
                        <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-3">Quantity Akhir Hari</h2>
                        <div class="chart-wrapper">
                            <canvas id="chartAkhir"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-5xl text-slate-300 mb-2"></i>
                    <p class="text-slate-500 text-base">Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
                </div>
            </div>
        @endif
    @else
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-indigo-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Pilih Filter untuk Melihat Chart Quantity On Hand</h3>
                <p class="text-slate-500">Silakan pilih produk, store, atau rentang tanggal dari filter di atas untuk menampilkan analisis quantity awal dan akhir hari.</p>
            </div>
        </div>
    @endif

    @slot('scripts')
        <script>
            // Pass data from Blade to JavaScript
            window.dashboardData = {
                grossProfit: @json((request('gross_product_key') || request('gross_start_date') || request('gross_end_date')) && isset($chartData) && !empty($chartData) && !empty($chartData['values']) ? $chartData : null),
                chartAwal: @json((request('inventory_product_key') || request('inventory_store_key') || request('inventory_start_date') || request('inventory_end_date')) && isset($chartAwal) && !empty($chartAwal) && !empty($chartAwal['values']) ? $chartAwal : null),
                chartAkhir: @json((request('inventory_product_key') || request('inventory_store_key') || request('inventory_start_date') || request('inventory_end_date')) && isset($chartAkhir) && !empty($chartAkhir) && !empty($chartAkhir['values']) ? $chartAkhir : null)
            };
        </script>
        <script src="js/dashboard.js"></script>
    @endslot
</x-layout>
