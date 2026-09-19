<aside class="w-64 bg-coffee-950 text-cream-100 flex flex-col justify-between shrink-0 select-none border-r border-coffee-900/60 shadow-2xl z-20">
    <!-- Top Branding & Profile -->
    <div class="p-5 flex flex-col gap-6">
        <!-- Brand Header -->
        <div class="flex items-center gap-3.5 px-2">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-caramel-500 to-coffee-700 flex items-center justify-center shadow-lg shadow-caramel-500/20 ring-2 ring-caramel-400/30">
                <svg class="w-6 h-6 text-cream-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                    <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                    <line x1="6" y1="2" x2="6" y2="4"></line>
                    <line x1="10" y1="2" x2="10" y2="4"></line>
                    <line x1="14" y1="2" x2="14" y2="4"></line>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight text-white leading-tight">KOPI SENJA</h1>
                <p class="text-[11px] font-semibold tracking-widest text-caramel-400 uppercase">Coffee & More</p>
            </div>
        </div>

        <!-- Cashier & Store Status Card -->
        <div class="bg-coffee-900/80 rounded-2xl p-3.5 border border-coffee-800/80 flex items-center justify-between shadow-inner">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-coffee-800 to-caramel-600 flex items-center justify-center font-bold text-sm text-cream-50 shrink-0 border border-coffee-700">
                    {{ substr($cashier->name ?? 'RK', 0, 2) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-cream-50 truncate">{{ $cashier->name ?? 'Rian Kasir' }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-medium text-emerald-400">Toko Buka</span>
                    </div>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-md bg-coffee-800 text-[10px] font-bold text-coffee-200 border border-coffee-700">
                Shift 1
            </span>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex flex-col gap-1.5">
            <p class="text-[11px] font-bold tracking-wider text-coffee-400 uppercase px-3 mb-1">Menu Utama</p>

            <!-- 1. Kasir / POS -->
            <a href="{{ route('pos.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 group {{ request()->routeIs('pos.*') ? 'bg-gradient-to-r from-caramel-500 to-caramel-600 text-white shadow-lg shadow-caramel-600/30' : 'text-coffee-200 hover:bg-coffee-900/60 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('pos.*') ? 'text-white' : 'text-coffee-400 group-hover:text-caramel-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                    <line x1="7" y1="15" x2="7.01" y2="15"></line>
                    <line x1="11" y1="15" x2="13" y2="15"></line>
                </svg>
                <span>Kasir / POS</span>
                @if(request()->routeIs('pos.*'))
                    <span class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></span>
                @endif
            </a>

            <!-- 2. Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-caramel-500 to-caramel-600 text-white shadow-lg shadow-caramel-600/30' : 'text-coffee-200 hover:bg-coffee-900/60 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-coffee-400 group-hover:text-caramel-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <rect x="3" y="3" width="7" height="9" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="5" rx="1"></rect>
                    <rect x="14" y="12" width="7" height="9" rx="1"></rect>
                    <rect x="3" y="16" width="7" height="5" rx="1"></rect>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 3. Produk & Menu -->
            <a href="{{ route('products.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-gradient-to-r from-caramel-500 to-caramel-600 text-white shadow-lg shadow-caramel-600/30' : 'text-coffee-200 hover:bg-coffee-900/60 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('products.*') ? 'text-white' : 'text-coffee-400 group-hover:text-caramel-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 2l9 4.9V17L12 22l-9-5V6.9L12 2z"></path>
                    <path d="M12 12l9-4.9"></path>
                    <path d="M12 12v10"></path>
                    <path d="M12 12L3 7.1"></path>
                </svg>
                <span>Produk & Menu</span>
            </a>

            <!-- 4. Riwayat Transaksi -->
            <a href="{{ route('transactions.index') }}" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all duration-200 group {{ request()->routeIs('transactions.*') ? 'bg-gradient-to-r from-caramel-500 to-caramel-600 text-white shadow-lg shadow-caramel-600/30' : 'text-coffee-200 hover:bg-coffee-900/60 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('transactions.*') ? 'text-white' : 'text-coffee-400 group-hover:text-caramel-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path d="M12 8v4l3 3"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                </svg>
                <span>Riwayat Transaksi</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Info & Realtime Clock -->
    <div class="p-4 border-t border-coffee-900/80 bg-coffee-950/60 flex flex-col gap-3">
        <div class="flex items-center justify-between text-xs text-coffee-300 font-medium px-2" x-data="{
            time: '',
            updateTime() {
                const now = new Date();
                this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
        }" x-init="updateTime(); setInterval(() => updateTime(), 1000)">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-caramel-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Waktu Kasir</span>
            </span>
            <span class="font-mono font-bold text-cream-50 bg-coffee-900 px-2 py-0.5 rounded border border-coffee-800" x-text="time">--:--:--</span>
        </div>

        <div class="text-[11px] text-coffee-400 text-center font-medium">
            Kopi Senja POS v2.4 • Laravel 13
        </div>
    </div>
</aside>
