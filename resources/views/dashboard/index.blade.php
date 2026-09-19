<x-layouts.app title="Dashboard Ringkas — Kopi Senja">
    <div class="p-8 max-w-7xl mx-auto w-full space-y-8" x-data="{
        chartTab: 'today',
        hourlyData: {{ Js::from($hourlyBuckets) }},
        weeklyData: {{ Js::from($weeklyBuckets) }},
        formatRupiah(val) {
            return window.formatRupiah(val);
        }
    }">

        <!-- Top Welcome & Quick Actions Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-caramel-100 text-caramel-800 text-[11px] font-extrabold uppercase tracking-wider border border-caramel-200/80">
                        Shift 1 • Kasir Aktif
                    </span>
                    <span class="text-xs text-coffee-500 font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="text-3xl font-black text-coffee-950 tracking-tight">Dashboard Ringkas</h1>
                <p class="text-sm font-semibold text-coffee-600">Ringkasan performa penjualan dan operasional cafe hari ini.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('pos.index') }}" 
                   class="px-5 py-3 rounded-2xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-sm shadow-xl shadow-caramel-900/15 flex items-center gap-2.5 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    <span>Buka Kasir / POS</span>
                </a>
            </div>
        </div>

        <!-- 4 Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- 1. Penjualan Hari Ini -->
            <x-stat-card 
                title="Penjualan Hari Ini" 
                :value="'Rp ' . number_format($todaySales, 0, ',', '.')" 
                :trend="$salesTrend" 
                :trendUp="$salesTrend >= 0" 
                subtitle="vs kemarin"
                iconBg="bg-caramel-100 text-caramel-800"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </x-stat-card>

            <!-- 2. Total Transaksi -->
            <x-stat-card 
                title="Total Transaksi" 
                :value="$todayCount . ' Pesanan'" 
                :trend="$countTrend" 
                :trendUp="$countTrend >= 0" 
                subtitle="vs kemarin"
                iconBg="bg-coffee-100 text-coffee-800"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </x-stat-card>

            <!-- 3. Produk Terjual -->
            <x-stat-card 
                title="Produk Terjual" 
                :value="$todayItemsSold . ' Items / Cups'" 
                :trend="14.2" 
                :trendUp="true" 
                subtitle="volume penjualan"
                iconBg="bg-sage-100 text-sage-700"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </x-stat-card>

            <!-- 4. Rata-rata Transaksi (AOV) -->
            <x-stat-card 
                title="Rata-rata Transaksi" 
                :value="'Rp ' . number_format($averageOrderValue, 0, ',', '.')" 
                :trend="5.8" 
                :trendUp="true" 
                subtitle="AOV cafe"
                iconBg="bg-cream-200 text-coffee-900"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </x-stat-card>
        </div>

        <!-- Grafik Penjualan Interaktif -->
        <div class="bg-white rounded-3xl p-6 border border-[#EAE2D5] shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black text-coffee-950">Grafik Penjualan</h2>
                    <p class="text-xs font-semibold text-coffee-500">Tren pendapatan cafe berdasarkan waktu</p>
                </div>

                <!-- Chart Filter Tabs -->
                <div class="flex items-center gap-1.5 p-1 rounded-xl bg-[#FAF7F2] border border-[#EAE2D5]">
                    <button 
                        @click="chartTab = 'today'" 
                        :class="chartTab === 'today' ? 'bg-coffee-900 text-white font-bold shadow-sm' : 'text-coffee-600 hover:text-coffee-900 font-semibold'"
                        class="px-3.5 py-1.5 rounded-lg text-xs transition-all"
                    >Hari Ini</button>
                    <button 
                        @click="chartTab = 'week'" 
                        :class="chartTab === 'week' ? 'bg-coffee-900 text-white font-bold shadow-sm' : 'text-coffee-600 hover:text-coffee-900 font-semibold'"
                        class="px-3.5 py-1.5 rounded-lg text-xs transition-all"
                    >Minggu Ini</button>
                </div>
            </div>

            <!-- Tab 1: Hari Ini (Per Jam) -->
            <div x-show="chartTab === 'today'" class="pt-4">
                <div class="h-64 flex items-end justify-between gap-3 px-2 border-b border-[#F0EAE1] pb-2">
                    <template x-for="(val, hour) in hourlyData" :key="hour">
                        <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end relative">
                            <!-- Tooltip hover -->
                            <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 bg-coffee-950 text-cream-50 text-[10px] font-mono py-1 px-2 rounded-lg shadow-lg whitespace-nowrap">
                                <span x-text="formatRupiah(val)"></span>
                            </div>

                            <!-- Bar Column -->
                            <div class="w-full max-w-[42px] bg-cream-100 rounded-t-xl overflow-hidden h-full flex items-end">
                                <div 
                                    class="w-full bg-gradient-to-t from-caramel-600 to-caramel-400 group-hover:from-coffee-900 group-hover:to-caramel-500 rounded-t-xl transition-all duration-300"
                                    :style="`height: ${Math.max(8, Math.min(100, (val / 1500000) * 100))}%`"
                                ></div>
                            </div>
                            
                            <span class="text-[11px] font-bold text-coffee-500" x-text="hour"></span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Tab 2: Minggu Ini (Harian) -->
            <div x-show="chartTab === 'week'" style="display: none;" class="pt-4">
                <div class="h-64 flex items-end justify-between gap-3 px-2 border-b border-[#F0EAE1] pb-2">
                    <template x-for="day in weeklyData" :key="day.label">
                        <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end relative">
                            <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 bg-coffee-950 text-cream-50 text-[10px] font-mono py-1 px-2 rounded-lg shadow-lg whitespace-nowrap">
                                <span x-text="formatRupiah(day.total)"></span>
                            </div>

                            <div class="w-full max-w-[42px] bg-cream-100 rounded-t-xl overflow-hidden h-full flex items-end">
                                <div 
                                    class="w-full bg-gradient-to-t from-coffee-800 to-caramel-500 group-hover:from-coffee-950 group-hover:to-caramel-600 rounded-t-xl transition-all duration-300"
                                    :style="`height: ${Math.max(10, Math.min(100, (day.total / 3000000) * 100))}%`"
                                ></div>
                            </div>
                            
                            <span class="text-[11px] font-bold text-coffee-500" x-text="day.label"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bottom Split: Transaksi Terbaru & Produk Terlaris -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Transaksi Terbaru (2 Cols) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-[#EAE2D5] shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-coffee-950">Transaksi Terbaru</h3>
                        <p class="text-xs font-semibold text-coffee-500">Aktivitas penjualan kasir terkini</p>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-caramel-600 hover:text-caramel-700 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#EAE2D5] text-coffee-500 font-extrabold uppercase tracking-wider">
                                <th class="pb-3">No. Transaksi</th>
                                <th class="pb-3">Waktu</th>
                                <th class="pb-3">Pelanggan</th>
                                <th class="pb-3">Total</th>
                                <th class="pb-3">Metode</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F2ECE1]">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-[#FCFAF6] transition-colors">
                                    <td class="py-3.5 font-mono font-bold text-coffee-950">
                                        <a href="{{ route('transactions.receipt', $order->id) }}" target="_blank" class="hover:text-caramel-600">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="py-3.5 text-coffee-500 font-medium">
                                        {{ $order->created_at->format('H:i') }}
                                    </td>
                                    <td class="py-3.5 font-bold text-coffee-900">
                                        {{ $order->customer_name }}
                                    </td>
                                    <td class="py-3.5 font-black text-coffee-950">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                            {{ $order->payment_method === 'cash' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $order->payment_method }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold flex items-center gap-1 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Selesai
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-coffee-400 font-semibold">Belum ada transaksi hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Produk Terlaris (1 Col) -->
            <div class="bg-white rounded-3xl p-6 border border-[#EAE2D5] shadow-sm space-y-4">
                <div>
                    <h3 class="text-base font-black text-coffee-950">Produk Terlaris</h3>
                    <p class="text-xs font-semibold text-coffee-500">Top 5 menu favorit pelanggan</p>
                </div>

                <div class="space-y-3.5">
                    @forelse($topProducts as $index => $item)
                        <div class="p-3 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] flex items-center justify-between gap-3 hover:border-caramel-300 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-7 h-7 rounded-xl {{ $index === 0 ? 'bg-caramel-500 text-white' : 'bg-cream-200 text-coffee-800' }} font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-extrabold text-coffee-950 truncate">{{ $item->product_name }}</h4>
                                    <span class="text-[11px] font-semibold text-coffee-500">{{ $item->total_qty }} cup / item terjual</span>
                                </div>
                            </div>

                            <span class="text-xs font-black text-coffee-900 shrink-0">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-coffee-400 py-6 text-center">Belum ada data penjualan.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-layouts.app>
