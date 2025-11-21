<x-layout :includeChartJs="true">

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
        }
        .btn-tab {
            transition: all 0.3s ease;
        }
        .btn-tab.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        }
        .btn-tab:not(.active) {
            background: white;
            color: #6366f1;
            border: 2px solid #6366f1;
        }
        .btn-tab:not(.active):hover {
            background: #f0f0ff;
        }
    </style>
    <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6 mb-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Rata-rata receipt to initial shipment lag tahun 2025 per warehouse</h2>
        </div>
        
        @if($avgReceiptToInitialLag->count() > 0)
            <!-- Bar Chart -->
            <div class="mb-6">
                <div class="bg-white rounded-2xl p-6 shadow-inner">
                    <canvas id="avgLagChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
            
            <!-- Table untuk rata-rata lag per warehouse -->
            <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-inner">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Warehouse Key</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Warehouse</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata Lag (Hari)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($avgReceiptToInitialLag as $avg)
                            <tr class="hover:bg-indigo-50/50 transition">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $avg->warehouse_key }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $avg->warehouse_name }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ number_format($avg->avg_lag, 2, ',', '.') }} hari
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-info-circle text-6xl text-indigo-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-700 mb-2">Tidak Ada Data untuk Tahun 2025</h3>
                <p class="text-slate-500">Tidak ada data receipt to initial shipment lag yang ditemukan untuk tahun 2025.</p>
            </div>
        @endif
    </section>

    <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900 mb-6"> Accumulation Inventory</h2>
        </div>
        <!-- Button Tabs -->
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('accumulation_inventory', ['type' => 'received', 'per_page' => $perPage]) }}" 
               class="btn-tab px-6 py-3 rounded-xl font-semibold {{ $type === 'received' ? 'active' : '' }}">
                Received
            </a>
            <a href="{{ route('accumulation_inventory', ['type' => 'inspected', 'per_page' => $perPage]) }}" 
               class="btn-tab px-6 py-3 rounded-xl font-semibold {{ $type === 'inspected' ? 'active' : '' }}">
                Inspected
            </a>
            <a href="{{ route('accumulation_inventory', ['type' => 'bin', 'per_page' => $perPage]) }}" 
               class="btn-tab px-6 py-3 rounded-xl font-semibold {{ $type === 'bin' ? 'active' : '' }}">
                Bin Placement
            </a>
            <a href="{{ route('accumulation_inventory', ['type' => 'first_shipment', 'per_page' => $perPage]) }}" 
               class="btn-tab px-6 py-3 rounded-xl font-semibold {{ $type === 'first_shipment' ? 'active' : '' }}">
                First Shipment
            </a>
            <a href="{{ route('accumulation_inventory', ['type' => 'last_shipment', 'per_page' => $perPage]) }}" 
               class="btn-tab px-6 py-3 rounded-xl font-semibold {{ $type === 'last_shipment' ? 'active' : '' }}">
                Last Shipment
            </a>
        </div>

        <!-- Header dengan Info -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    @if($type === 'received')
                        Data Received
                    @elseif($type === 'inspected')
                        Data Inspected
                    @elseif($type === 'bin')
                        Data Bin Placement
                    @elseif($type === 'first_shipment')
                        Data First Shipment
                    @elseif($type === 'last_shipment')
                        Data Last Shipment
                    @endif
                </h2>
                <p class="text-slate-500 text-sm">Menampilkan {{ $inventories->count() }} dari {{ $inventories->total() }} record</p>
            </div>
            <form method="GET" class="flex items-center gap-3">
                <input type="hidden" name="type" value="{{ $type }}">
                <label class="text-sm text-slate-500">Baris per halaman</label>
                <select name="per_page" onchange="this.form.submit()" class="border border-slate-200 rounded-2xl px-4 py-2 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-inner">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Product</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Perbedaan Hari</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($inventories as $inventory)
                        <tr class="hover:bg-indigo-50/50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $inventory->id }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $inventory->product_description }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $inventory->warehouse_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                @if($inventory->date_key && $inventory->date_key != 0)
                                    @php
                                        $dateKey = (string)$inventory->date_key;
                                        $formattedDate = \Carbon\Carbon::createFromFormat('Ymd', $dateKey)->format('Y-m-d');
                                    @endphp
                                    {{ $formattedDate }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                @if($inventory->quantity)
                                    {{ number_format($inventory->quantity, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                @if($inventory->receipt_lag)
                                    {{ $inventory->receipt_lag }} hari
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $inventories->links() }}
        </div>
    </section>

    @slot('scripts')
        @if($avgReceiptToInitialLag->count() > 0)
        <script>
            // Pass data from Blade to JavaScript
            window.avgLagData = @json($avgReceiptToInitialLag);
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const ctx = document.getElementById('avgLagChart');
                if (ctx && window.avgLagData && window.avgLagData.length > 0) {
                    const chartCtx = ctx.getContext('2d');
                    
                    const labels = window.avgLagData.map(item => item.warehouse_name);
                    const data = window.avgLagData.map(item => parseFloat(item.avg_lag));
                    const warehouseKeys = window.avgLagData.map(item => item.warehouse_key);
                    
                    const gradient = chartCtx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.7)');
                    gradient.addColorStop(1, 'rgba(168, 85, 247, 0.3)');
                    
                    new Chart(chartCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Rata-rata Lag (Hari)',
                                data: data,
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
                            animation: { 
                                duration: 1800, 
                                easing: 'easeOutQuart' 
                            },
                            plugins: {
                                legend: { 
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        },
                                        color: '#475569'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        title: items => {
                                            if (!items.length) return '';
                                            const idx = items[0].dataIndex;
                                            const name = labels[idx] ?? 'Warehouse';
                                            const key = warehouseKeys[idx] ?? null;
                                            return key ? `${name} (Key: ${key})` : name;
                                        },
                                        label: ctx => {
                                            const value = ctx.parsed.y;
                                            return `Rata-rata Lag: ${value.toFixed(2)} hari`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { 
                                        callback: v => v.toFixed(1) + ' hari',
                                        font: {
                                            size: 11
                                        },
                                        color: '#64748b'
                                    },
                                    grid: {
                                        color: 'rgba(148, 163, 184, 0.1)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11
                                        },
                                        color: '#64748b'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
            });
        </script>
        @endif
    @endslot
</x-layout>

