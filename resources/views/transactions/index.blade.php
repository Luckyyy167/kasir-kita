<x-layouts.app title="Riwayat Transaksi — Kopi Senja">
    <div class="p-8 max-w-7xl mx-auto w-full space-y-6" x-data="{
        detailModal: {
            show: false,
            loading: false,
            order: null
        },
        async openDetail(orderId) {
            this.detailModal.show = true;
            this.detailModal.loading = true;
            try {
                const res = await fetch(`/transactions/${orderId}`);
                const data = await res.json();
                this.detailModal.order = data.order;
            } catch(e) {
                alert('Gagal memuat detail transaksi: ' + e.message);
                this.detailModal.show = false;
            } finally {
                this.detailModal.loading = false;
            }
        },
        formatRupiah(val) {
            return window.formatRupiah(val);
        }
    }">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-coffee-950 tracking-tight">Riwayat Transaksi</h1>
                <p class="text-sm font-semibold text-coffee-600">Catatan riwayat transaksi penjualan kasir Kopi Senja.</p>
            </div>

            <a href="{{ route('pos.index') }}" 
               class="px-5 py-2.5 rounded-2xl bg-coffee-900 hover:bg-coffee-950 text-white font-extrabold text-xs shadow-lg flex items-center gap-2 self-start">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Transaksi Baru di POS</span>
            </a>
        </div>

        <!-- 4 Summary Metrics Banner -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm">
                <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Total Omset Terfilter</span>
                <span class="text-xl font-black text-coffee-950 mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm">
                <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Total Transaksi</span>
                <span class="text-xl font-black text-coffee-950 mt-1 block">{{ $totalOrders }} Pesanan</span>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm">
                <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Transaksi Non-Tunai</span>
                <span class="text-xl font-black text-caramel-600 mt-1 block">{{ $cashlessPercentage }}%</span>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm">
                <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Rata-rata Order (AOV)</span>
                <span class="text-xl font-black text-coffee-950 mt-1 block">Rp {{ number_format($avgTicket, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-3xl p-5 border border-[#EAE2D5] shadow-sm space-y-4">
            <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 justify-between">
                <!-- Search input -->
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-coffee-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari ID transaksi, nama pelanggan, kasir..." 
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-semibold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                    >
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Period Filter -->
                    <select name="period" class="py-2 px-3 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-bold text-coffee-800 focus:outline-none">
                        <option value="all" {{ request('period') === 'all' ? 'selected' : '' }}>Semua Periode</option>
                        <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('period') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('period') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>

                    <!-- Payment Method Filter -->
                    <select name="payment_method" class="py-2 px-3 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-bold text-coffee-800 focus:outline-none">
                        <option value="all" {{ request('payment_method') === 'all' ? 'selected' : '' }}>Semua Metode</option>
                        <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="debit" {{ request('payment_method') === 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="credit" {{ request('payment_method') === 'credit' ? 'selected' : '' }}>Kredit</option>
                        <option value="ewallet" {{ request('payment_method') === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                    </select>

                    <button type="submit" class="py-2 px-4 rounded-xl bg-coffee-900 text-white font-bold text-xs hover:bg-coffee-950 transition-colors">
                        Terapkan
                    </button>

                    @if(request()->hasAny(['search', 'period', 'payment_method', 'status']))
                        <a href="{{ route('transactions.index') }}" class="py-2 px-3 rounded-xl border border-coffee-200 text-coffee-600 text-xs font-bold hover:bg-cream-100">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Transactions Table Card -->
        <div class="bg-white rounded-3xl border border-[#EAE2D5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#FAF7F2] border-b border-[#EAE2D5] text-coffee-500 font-extrabold uppercase tracking-wider">
                            <th class="py-3.5 px-6">ID Transaksi</th>
                            <th class="py-3.5 px-4">Tanggal & Waktu</th>
                            <th class="py-3.5 px-4">Kasir</th>
                            <th class="py-3.5 px-4">Pelanggan</th>
                            <th class="py-3.5 px-4">Item Dipesan</th>
                            <th class="py-3.5 px-4">Total</th>
                            <th class="py-3.5 px-4">Pembayaran</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F2ECE1]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#FCFAF6] transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-coffee-950">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-4 px-4 text-coffee-600 font-medium whitespace-nowrap">
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-coffee-400 font-mono">{{ $order->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="py-4 px-4 font-bold text-coffee-800">
                                    {{ $order->user->name ?? 'Kasir' }}
                                </td>
                                <td class="py-4 px-4 font-semibold text-coffee-900">
                                    <div>{{ $order->customer_name }}</div>
                                    <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider {{ $order->order_type === 'dine_in' ? 'bg-amber-100 text-amber-800' : 'bg-cream-200 text-coffee-700' }}">
                                        {{ $order->order_type === 'dine_in' ? 'Dine In' : 'Take Away' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-coffee-700 font-medium max-w-xs">
                                    <div class="truncate">
                                        {{ $order->items->map(fn($i) => "{$i->quantity}x {$i->product_name}")->join(', ') }}
                                    </div>
                                    <span class="text-[10px] text-coffee-400 font-medium">{{ $order->items->sum('quantity') }} total item</span>
                                </td>
                                <td class="py-4 px-4 font-black text-coffee-950 whitespace-nowrap">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $order->payment_method === 'cash' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $order->payment_method }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Detail Button -->
                                        <button 
                                            @click="openDetail({{ $order->id }})"
                                            class="px-2.5 py-1.5 rounded-lg bg-cream-100 hover:bg-caramel-100 text-coffee-800 font-bold text-xs transition-colors"
                                        >
                                            Detail
                                        </button>

                                        <!-- Receipt Print Button -->
                                        <a 
                                            href="{{ route('transactions.receipt', $order->id) }}" 
                                            target="_blank"
                                            class="p-1.5 rounded-lg bg-cream-100 hover:bg-coffee-900 hover:text-white text-coffee-700 font-bold transition-colors"
                                            title="Cetak Struk"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-coffee-400 font-bold">
                                    Tidak ada data transaksi yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#EAE2D5] bg-[#FAF7F2]">
                {{ $orders->links() }}
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- DETAIL TRANSAKSI MODAL                                                   -->
        <!-- ========================================================================= -->
        <div 
            x-show="detailModal.show" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/60 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="detailModal.show = false"
                class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-[#EAE2D5]"
            >
                <div class="p-6 pb-4 border-b border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-caramel-600" x-text="detailModal.order?.order_number"></span>
                        <h3 class="text-lg font-black text-coffee-950">Rincian Transaksi</h3>
                    </div>
                    <button 
                        @click="detailModal.show = false" 
                        class="w-8 h-8 rounded-full bg-cream-200 text-coffee-700 hover:bg-cream-300 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[65vh] overflow-y-auto">
                    <template x-if="detailModal.loading">
                        <div class="py-12 text-center text-coffee-400 font-bold">Memuat rincian transaksi...</div>
                    </template>

                    <template x-if="!detailModal.loading && detailModal.order">
                        <div class="space-y-4">
                            <!-- Info Summary Card -->
                            <div class="p-4 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-coffee-500 block">Pelanggan:</span>
                                    <span class="font-bold text-coffee-950" x-text="detailModal.order.customer_name"></span>
                                </div>
                                <div>
                                    <span class="text-coffee-500 block">Tipe Pesanan:</span>
                                    <span class="font-bold text-coffee-950 uppercase" x-text="detailModal.order.order_type"></span>
                                </div>
                                <div>
                                    <span class="text-coffee-500 block">Kasir:</span>
                                    <span class="font-bold text-coffee-950" x-text="detailModal.order.user?.name || 'Kasir'"></span>
                                </div>
                                <div>
                                    <span class="text-coffee-500 block">Metode Bayar:</span>
                                    <span class="font-bold text-coffee-950 uppercase" x-text="detailModal.order.payment_method"></span>
                                </div>
                            </div>

                            <!-- Items List -->
                            <div>
                                <h4 class="text-xs font-extrabold text-coffee-600 uppercase tracking-wider mb-2">Item Pesanan</h4>
                                <div class="space-y-2">
                                    <template x-for="item in detailModal.order.items" :key="item.id">
                                        <div class="p-3 rounded-xl bg-[#FAF7F2] border border-[#EAE2D5] flex items-start justify-between gap-3 text-xs">
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-extrabold text-coffee-950" x-text="item.quantity + 'x ' + item.product_name"></span>
                                                    <template x-if="item.temperature">
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-cream-200 text-coffee-800" x-text="item.temperature"></span>
                                                    </template>
                                                </div>
                                                
                                                <template x-if="item.modifiers && item.modifiers.length > 0">
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        <template x-for="mod in item.modifiers" :key="mod.id">
                                                            <span class="text-[10px] text-caramel-700 font-semibold" x-text="'+ ' + mod.modifier_name"></span>
                                                        </template>
                                                    </div>
                                                </template>

                                                <template x-if="item.notes">
                                                    <p class="text-[10px] text-coffee-500 italic mt-0.5" x-text="item.notes"></p>
                                                </template>
                                            </div>

                                            <span class="font-black text-coffee-950" x-text="formatRupiah(item.subtotal)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Cost Breakdown -->
                            <div class="p-4 rounded-2xl bg-cream-50 border border-[#EFE7DC] space-y-1.5 text-xs">
                                <div class="flex justify-between text-coffee-600">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-coffee-900" x-text="formatRupiah(detailModal.order.subtotal)"></span>
                                </div>
                                <div class="flex justify-between text-coffee-600">
                                    <span>Diskon</span>
                                    <span class="font-bold text-coffee-900" x-text="'- ' + formatRupiah(detailModal.order.discount)"></span>
                                </div>
                                <div class="flex justify-between text-coffee-600">
                                    <span>PPN (10%)</span>
                                    <span class="font-bold text-coffee-900" x-text="formatRupiah(detailModal.order.tax)"></span>
                                </div>
                                <div class="flex justify-between text-coffee-600">
                                    <span>Service Charge (2%)</span>
                                    <span class="font-bold text-coffee-900" x-text="formatRupiah(detailModal.order.service_charge)"></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-[#EAE2D5] text-sm">
                                    <span class="font-black text-coffee-950">TOTAL</span>
                                    <span class="font-black text-caramel-600" x-text="formatRupiah(detailModal.order.total)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-4 border-t border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-end gap-2">
                    <template x-if="detailModal.order">
                        <a 
                            :href="`/transactions/${detailModal.order.id}/receipt`" 
                            target="_blank" 
                            class="px-4 py-2 rounded-xl bg-coffee-900 text-white font-bold text-xs flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Struk
                        </a>
                    </template>
                    <button 
                        @click="detailModal.show = false" 
                        class="px-4 py-2 rounded-xl border border-coffee-200 text-coffee-700 font-bold text-xs hover:bg-white"
                    >Tutup</button>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
