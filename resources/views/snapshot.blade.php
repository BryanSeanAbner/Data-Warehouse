<x-layout :includeChartJs="true">

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
        }
    </style>

    <!-- Filter Form -->
    <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6 mb-6">
        <h2 class="text-2xl font-bold text-slate-900 mb-6 flex items-center gap-3">
            <i class="fas fa-filter text-indigo-600"></i> Filter Data
        </h2>

        <form method="GET" action="{{ route('snapshot') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-2 text-indigo-600"></i>Produk
                </label>
                <select name="product_key" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
                    <option value="">Semua Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->product_key }}" {{ $productKey == $product->product_key ? 'selected' : '' }}>
                            {{ $product->product_description }}
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2 text-indigo-600"></i>Tanggal Selesai
                </label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-4 focus:ring-indigo-200 focus:border-indigo-500 transition">
            </div>

            <div class="flex items-end gap-3 md:col-span-4">
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold shadow-lg flex items-center gap-2 hover:bg-indigo-700 transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('snapshot') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition font-medium">
                    Reset
                </a>
            </div>
        </form>
    </section>

    <!-- Results Chart -->
    @if($productKey || $storeKey || $startDate || $endDate)
        <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-2 flex items-center gap-3">
                <i class="fas fa-chart-bar text-indigo-600"></i> Number of Turns
            </h2>
            <p class="text-slate-500 text-sm mb-6">
                Menampilkan number of turns berdasarkan tanggal
            </p>

            @if($snapshotData->count() > 0)
                <!-- Chart Container -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-inner mt-4">
                    <canvas id="snapshotChart" style="max-height: 500px;"></canvas>
                </div>

                <!-- Chart Data for JavaScript -->
                @php
                    $chartData = array();
                    foreach ($snapshotData as $item) {
                        $date = isset($item->date) ? (string)$item->date : '';
                        if (!empty($date) && strpos($date, ' ') !== false) {
                            $dateParts = explode(' ', $date);
                            $date = $dateParts[0];
                        }
                        $numberOfTurns = isset($item->avg_number_of_turns) ? (float)$item->avg_number_of_turns : 0.0;
                        $chartData[] = array($date, $numberOfTurns);
                    }
                @endphp
                <script>
                    window.snapshotChartData = @json($chartData);
                </script>
            @else
                <div class="bg-white rounded-2xl p-12 border border-slate-100 shadow-inner text-center mt-4">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fas fa-inbox text-5xl text-slate-300 mb-2"></i>
                        <p class="text-slate-500 text-base">Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
                    </div>
                </div>
            @endif
        </section>
    @else
        <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-indigo-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Pilih Filter untuk Melihat Data</h3>
                <p class="text-slate-500">Silakan pilih produk, store, atau rentang tanggal dari filter di atas untuk menampilkan analisis number of turns.</p>
            </div>
        </section>
    @endif

    @slot('scripts')
        @if($snapshotData->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('snapshotChart');
                if (!ctx || !window.snapshotChartData) return;

                const chartCtx = ctx.getContext('2d');
                
                // Prepare data - X axis: dates, Y axis: number of turns
                const labels = window.snapshotChartData.map(item => item[0]);
                const data = window.snapshotChartData.map(item => item[1]);
                
                // Create gradient
                const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
                gradient.addColorStop(1, 'rgba(168, 85, 247, 0.4)');

                new Chart(chartCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Number of Turns',
                            data: data,
                            backgroundColor: gradient,
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 2,
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
                                        return 'Number of Turns: ' + context.parsed.y.toFixed(4);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    },
                                    color: '#475569'
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    },
                                    color: '#64748b',
                                    maxRotation: 45,
                                    minRotation: 45
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Number of Turns',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    },
                                    color: '#475569'
                                },
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toFixed(4);
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
                            }
                        }
                    }
                });
            });
        </script>
        @endif
    @endslot
</x-layout>
