<x-layout :includeChartJs="false">

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
        }
    </style>

    <section class="glass-card rounded-3xl shadow-2xl border border-white/60 p-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Ringkasan Tanggal</h2>
                <p class="text-slate-500 text-sm">Menampilkan {{ $dates->count() }} dari {{ $dates->total() }} record</p>
            </div>
            <form method="GET" class="flex items-center gap-3">
                <label class="text-sm text-slate-500">Baris per halaman</label>
                <select name="per_page" onchange="this.form.submit()" class="border border-slate-200 rounded-2xl px-4 py-2 text-sm focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-100 shadow-inner">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Key</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Fiscal Quarter</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Holiday</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Weekday</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($dates as $date)
                        <tr class="hover:bg-indigo-50/50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $date->date_key }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ \Carbon\Carbon::parse($date->date)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $date->day_of_week }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $date->calendar_month_name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $date->calendar_year }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $date->fiscal_year_quarter }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $date->holiday_indicator === 'Holiday' ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $date->holiday_indicator === 'Holiday' ? 'Holiday' : 'Non-holiday' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $date->weekday_indicator === 'Weekday' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                    {{ $date->weekday_indicator }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $dates->links() }}
        </div>
    </section>
</x-layout>
