<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hadoop ETL Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="px-6 py-4 border-b border-slate-800">
                <h1 class="text-xl font-bold">Data Warehouse</h1>
                <p class="text-sm text-slate-400">Stationery Store</p>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>

                <a href="{{ route('sales_fact_table') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-sm">Sales Fact Table</span>
                </a>

                <a href="{{ route('hadoop.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                    </svg>
                    <span class="text-sm font-semibold">Hadoop ETL</span>
                </a>
            </nav>

            <div class="px-4 py-3 border-t border-slate-800">
                <p class="text-xs text-slate-400">© 2025 Stationery Store</p>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <h2 class="text-2xl font-bold text-gray-900">Hadoop ETL Management</h2>
                <p class="text-sm text-gray-600 mt-1">CSV → Hadoop MapReduce → MySQL Pipeline</p>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">CSV Input Files</h3>
                        <div class="text-3xl font-bold text-blue-600">{{ count($inputFiles) }}</div>
                        <p class="text-xs text-gray-500 mt-1">Ready for processing</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Processed TSV Files</h3>
                        <div class="text-3xl font-bold text-green-600">{{ count($processedFiles) }}</div>
                        <p class="text-xs text-gray-500 mt-1">MapReduce output</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase mb-2">Total Imported</h3>
                        <div class="text-3xl font-bold text-purple-600">{{ number_format($totalImported) }}</div>
                        <p class="text-xs text-gray-500 mt-1">Records in database</p>
                    </div>
                </div>

                <!-- ETL Workflow -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">ETL Workflow</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex-1 text-center">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold">1. Upload CSV</p>
                        </div>
                        <div class="text-gray-400">→</div>
                        <div class="flex-1 text-center">
                            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold">2. Hadoop MapReduce</p>
                        </div>
                        <div class="text-gray-400">→</div>
                        <div class="flex-1 text-center">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold">3. Import to MySQL</p>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Upload & Input Files -->
                    <div class="space-y-6">
                        <!-- Upload CSV -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">📤 Upload CSV File</h3>
                            <form action="{{ route('hadoop.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                                    <input type="file" name="csv_file" accept=".csv" required
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    <p class="mt-1 text-xs text-gray-500">Format: transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount</p>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                    Upload CSV
                                </button>
                            </form>
                        </div>

                        <!-- Input Files List -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">📁 Input Files (CSV)</h3>
                            @if(count($inputFiles) > 0)
                            <div class="space-y-2">
                                @foreach($inputFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $file['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($file['size'] / 1024, 2) }} KB • {{ date('d M Y H:i', $file['modified']) }}</p>
                                    </div>
                                    <form action="{{ route('hadoop.delete') }}" method="POST" class="ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="file_path" value="{{ $file['name'] }}">
                                        <input type="hidden" name="file_type" value="input">
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus file ini?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada file CSV</p>
                            @endif
                        </div>

                        <!-- Export SQL to CSV -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Export SQL to CSV</h3>
                            <p class="text-sm text-gray-600 mb-4">Export data dari tabel retail_sales_fact ke CSV untuk re-processing</p>
                            <form action="{{ route('hadoop.export') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                    Export to CSV
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: MapReduce & Import -->
                    <div class="space-y-6">
                        <!-- Run MapReduce -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">⚙️ Run Hadoop MapReduce</h3>
                            <p class="text-sm text-gray-600 mb-4">Jalankan command berikut di WSL untuk memproses CSV dengan Hadoop:</p>
                            <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm mb-4 overflow-x-auto">
                                <code>cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts<br>bash run_etl_root.sh</code>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded-lg text-sm">
                                <strong>Info:</strong> MapReduce akan membaca semua CSV di folder input dan menghasilkan TSV di folder processed
                            </div>
                        </div>

                        <!-- Processed Files List -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">📦 Processed Files (TSV)</h3>
                            @if(count($processedFiles) > 0)
                            <div class="space-y-2">
                                @foreach($processedFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $file['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format($file['size'] / 1024, 2) }} KB • {{ date('d M Y H:i', $file['modified']) }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form action="{{ route('hadoop.import') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tsv_file" value="{{ $file['name'] }}">
                                            <button type="submit" class="text-green-600 hover:text-green-800" title="Import to MySQL">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('hadoop.delete') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="file_path" value="{{ $file['name'] }}">
                                            <input type="hidden" name="file_type" value="processed">
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus file ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada file TSV hasil MapReduce</p>
                            @endif
                        </div>

                        <!-- Last Import Info -->
                        @if($lastImport)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">📈 Last Import Info</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date Key:</span>
                                    <span class="font-semibold">{{ $lastImport->date_key }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Product Key:</span>
                                    <span class="font-semibold">{{ $lastImport->product_key }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sales Amount:</span>
                                    <span class="font-semibold">Rp {{ number_format($lastImport->extended_sales_amount) }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
