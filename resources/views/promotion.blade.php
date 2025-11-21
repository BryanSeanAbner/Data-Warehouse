<x-layout :includeChartJs="true">
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
        }
    </style>

    <!-- Filter Form -->
    <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6 mb-6">
        <form method="GET" action="{{ route('promotion') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags mr-2 text-indigo-600"></i>Promotion
                </label>
                <select name="promotion_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Pilih Promotion</option>
                    @foreach($promotions as $promotion)
                        <option value="{{ $promotion->promotion_key }}" {{ $promotionKey == $promotion->promotion_key ? 'selected' : '' }}>
                            {{ $promotion->promotion_name }} ({{ $promotion->promotion_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-store mr-2 text-indigo-600"></i>Store
                </label>
                <select name="store_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Semua Store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->store_key }}" {{ $storeKey == $store->store_key ? 'selected' : '' }}>
                            {{ $store->store_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold shadow-lg flex items-center gap-2 hover:bg-indigo-700 transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('promotion') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition font-medium">
                    Reset
                </a>
            </div>
        </form>

        @if($selectedPromotion || $selectedStore)
            <div class="mt-6 p-4 bg-indigo-50 rounded-xl border border-indigo-200">
                <div class="flex flex-wrap gap-4 text-sm">
                    @if($selectedPromotion)
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-indigo-700">Promotion:</span>
                            <span class="text-indigo-900">{{ $selectedPromotion->promotion_name }} ({{ $selectedPromotion->promotion_code }})</span>
                        </div>
                    @endif
                    @if($selectedStore)
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-indigo-700">Store:</span>
                            <span class="text-indigo-900">{{ $selectedStore->store_name }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </section>

    <!-- Results Chart -->
    @if($promotionKey)
        <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <i class="fas fa-chart-bar text-indigo-600"></i> Gross Profit Produk Promosi (Top 10)
                </h2>
                <p class="text-slate-500 text-sm">
                    Menampilkan {{ $promotedProducts->count() }} produk dengan gross profit tertinggi
                </p>
            </div>

            @if($promotedProducts->count() > 0)
                <!-- Chart Container -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-inner">
                    <canvas id="promotionChart" style="max-height: 500px;"></canvas>
                </div>

                <!-- Chart Data for JavaScript -->
                <script>
                    window.promotionChartData = @json($promotedProducts->map(function($product) {
                        return [
                            $product->product_description ?: ('Product ' . $product->product_key),
                            (float) $product->sum_gross_profit
                        ];
                    }));
                </script>
            @else
                <div class="bg-white rounded-2xl p-12 border border-slate-100 shadow-inner text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-inbox text-4xl text-slate-300"></i>
                        <p class="text-slate-500">Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
                    </div>
                </div>
            @endif
        </section>
    @else
        <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-indigo-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Pilih Promotion untuk Melihat Data</h3>
                <p class="text-slate-500">Silakan pilih promotion dari filter di atas untuk menampilkan analisis gross profit produk yang dipromosikan.</p>
            </div>
        </section>
    @endif

    @slot('scripts')
        @if($promotionKey && $promotedProducts->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('promotionChart');
                if (!ctx || !window.promotionChartData) return;

                const chartCtx = ctx.getContext('2d');
                
                // Prepare data
                const labels = window.promotionChartData.map(item => item[0]);
                const data = window.promotionChartData.map(item => item[1]);
                
                // Create gradient
                const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
                gradient.addColorStop(1, 'rgba(168, 85, 247, 0.4)');

                new Chart(chartCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Gross Profit (Rp)',
                            data: data,
                            backgroundColor: gradient,
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Horizontal bar chart
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 1.5,
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    },
                                    color: '#475569'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: '600'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        return 'Gross Profit: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.x);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    },
                                    font: {
                                        size: 11
                                    },
                                    color: '#64748b'
                                },
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.2)',
                                    drawBorder: false
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: 11
                                    },
                                    color: '#64748b',
                                    maxRotation: 0,
                                    autoSkip: false
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endif
    @endslot

    <!-- Tabel Produk yang Tidak Terjual -->
    @if($promotionKey && isset($unsoldProducts))
        <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6 mt-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                    <i class="fas fa-exclamation-triangle text-orange-600"></i> Produk yang Tidak Terjual pada Masa Promosi
                </h2>
                <p class="text-slate-500 text-sm">
                    Menampilkan {{ $unsoldProducts->count() }} produk yang dipromosikan tetapi tidak terjual selama periode promosi
                </p>
            </div>

            @if($unsoldProducts->count() > 0)
                <div class="overflow-x-auto bg-white rounded-2xl border border-slate-100 shadow-inner">
                    <table class="w-full">
                        <thead class="bg-indigo-50 border-b-2 border-indigo-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-900 uppercase tracking-wider">
                                    <i class="fas fa-barcode mr-2"></i>SKU
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-900 uppercase tracking-wider">
                                    <i class="fas fa-box mr-2"></i>Deskripsi Produk
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-900 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-2"></i>Brand
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-900 uppercase tracking-wider">
                                    <i class="fas fa-folder mr-2"></i>Kategori
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-900 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-2"></i>Periode Promosi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($unsoldProducts as $product)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-slate-900">{{ $product->sku_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-700">{{ $product->product_description }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-slate-600">{{ $product->brand_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-slate-600">{{ $product->category_name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-slate-500">
                                                {{ \Carbon\Carbon::parse($product->promotion_begin_date)->format('d M Y') }}
                                            </span>
                                            <span class="text-xs text-slate-400">s/d</span>
                                            <span class="text-xs text-slate-500">
                                                {{ \Carbon\Carbon::parse($product->promotion_end_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 border border-slate-100 shadow-inner text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-check-circle text-4xl text-green-400"></i>
                        <p class="text-slate-500 font-medium">Semua produk yang dipromosikan telah terjual selama periode promosi.</p>
                    </div>
                </div>
            @endif
        </section>
    @endif
</x-layout>

