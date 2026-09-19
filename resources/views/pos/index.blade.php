<x-layouts.app title="Kasir POS — Kopi Senja">
    <div class="flex h-full min-w-0" x-data="posApp({
        categories: {{ Js::from($categories) }},
        products: {{ Js::from($products) }},
        nextOrderNumber: '{{ $nextOrderNumber }}',
        csrfToken: '{{ csrf_token() }}',
        checkoutUrl: '{{ route('pos.checkout') }}'
    })">

        <!-- ========================================================================= -->
        <!-- MIDDLE COLUMN: PRODUCT CATALOG AREA                                      -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0 h-full border-r border-[#EFE8DE] bg-[#FAF7F2]">
            
            <!-- Top Header & Search Bar -->
            <header class="p-6 pb-4 border-b border-[#EFE8DE] bg-white/70 backdrop-blur-md sticky top-0 z-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-coffee-950 tracking-tight">Katalog Menu</h2>
                        <p class="text-xs font-semibold text-coffee-600">Pilih menu untuk pesanan pelanggan</p>
                    </div>

                    <!-- Search Input -->
                    <div class="relative w-full sm:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-coffee-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            placeholder="Cari kopi, pastry, makanan..." 
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-[#F5EFE6]/70 border border-[#E8DFCFC] text-sm text-coffee-950 placeholder-coffee-400 focus:outline-none focus:ring-2 focus:ring-caramel-500/40 focus:border-caramel-500 transition-all shadow-sm"
                        >
                        <button 
                            x-show="searchQuery.length > 0" 
                            @click="searchQuery = ''" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-coffee-400 hover:text-coffee-600"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pt-4 pb-1">
                    <button 
                        @click="selectedCategory = 'all'" 
                        :class="selectedCategory === 'all' 
                            ? 'bg-coffee-900 text-white shadow-md shadow-coffee-900/20 ring-1 ring-coffee-800' 
                            : 'bg-white text-coffee-700 hover:bg-[#F2ECE1] border border-[#EAE2D5]'"
                        class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 flex items-center gap-2 cursor-pointer"
                    >
                        <span>Semua Menu</span>
                        <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="selectedCategory === 'all' ? 'bg-caramel-500 text-white' : 'bg-coffee-100 text-coffee-700'" x-text="products.length"></span>
                    </button>

                    <template x-for="cat in categories" :key="cat.id">
                        <button 
                            @click="selectedCategory = cat.id" 
                            :class="selectedCategory === cat.id 
                                ? 'bg-coffee-900 text-white shadow-md shadow-coffee-900/20 ring-1 ring-coffee-800' 
                                : 'bg-white text-coffee-700 hover:bg-[#F2ECE1] border border-[#EAE2D5]'"
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 flex items-center gap-2 cursor-pointer"
                        >
                            <span x-text="cat.name"></span>
                            <span class="px-1.5 py-0.5 rounded-full text-[10px]" :class="selectedCategory === cat.id ? 'bg-caramel-500 text-white' : 'bg-coffee-100 text-coffee-700'" x-text="cat.products_count"></span>
                        </button>
                    </template>
                </div>
            </header>

            <!-- Product Cards Grid -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Empty State -->
                <div x-show="filteredProducts.length === 0" class="h-64 flex flex-col items-center justify-center text-center p-6 text-coffee-500" style="display: none;">
                    <div class="w-16 h-16 rounded-full bg-cream-200/80 flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-coffee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-coffee-900">Menu tidak ditemukan</h3>
                    <p class="text-xs text-coffee-500 mt-1 max-w-xs">Coba gunakan kata kunci lain atau pilih kategori yang berbeda.</p>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div 
                            @click="handleProductClick(product)"
                            class="group bg-white rounded-2xl p-3.5 border border-[#EAE2D5] hover:border-caramel-400/80 hover:shadow-xl hover:shadow-caramel-900/5 transition-all duration-200 cursor-pointer flex flex-col justify-between relative overflow-hidden"
                        >
                            <!-- Thumbnail & Category Pill -->
                            <div>
                                <div class="relative w-full h-36 rounded-xl overflow-hidden bg-cream-100 mb-3">
                                    <img 
                                        :src="product.image || 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500'" 
                                        :alt="product.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                    <span 
                                        class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-coffee-950/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider"
                                        x-text="product.category ? product.category.name : 'Cafe'"
                                    ></span>

                                    <!-- Modifier Badge Indicator -->
                                    <template x-if="product.modifiers && product.modifiers.length > 0">
                                        <span class="absolute bottom-2 right-2 px-2 py-0.5 rounded-md bg-caramel-500 text-white text-[10px] font-bold flex items-center gap-1 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                            Kustomisasi
                                        </span>
                                    </template>
                                </div>

                                <h3 class="font-extrabold text-sm text-coffee-950 leading-snug group-hover:text-caramel-600 transition-colors line-clamp-1" x-text="product.name"></h3>
                                <p class="text-[11px] text-coffee-500 mt-1 line-clamp-2 leading-tight" x-text="product.description || 'Pilihan lezat khas Kopi Senja.'"></p>
                            </div>

                            <!-- Bottom Price & Quick Add Button -->
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[#F2ECE1]">
                                <div>
                                    <span class="text-[10px] font-bold text-coffee-400 block uppercase tracking-wide">Harga</span>
                                    <span class="text-sm font-black text-coffee-900" x-text="formatRupiah(product.price)"></span>
                                </div>

                                <button 
                                    class="w-8 h-8 rounded-xl bg-cream-100 hover:bg-caramel-500 text-coffee-700 hover:text-white flex items-center justify-center transition-all duration-200 group-hover:bg-caramel-500 group-hover:text-white shadow-sm"
                                    title="Tambah ke Keranjang"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- RIGHT COLUMN: CART / ORDER SECTION                                       -->
        <!-- ========================================================================= -->
        <div class="w-96 shrink-0 h-full flex flex-col bg-white border-l border-[#EFE8DE] shadow-2xl z-10">
            
            <!-- Cart Header -->
            <div class="p-5 pb-4 border-b border-[#EFE8DE] bg-[#FAF7F2]">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-caramel-500"></span>
                        <h3 class="font-extrabold text-base text-coffee-950">Pesanan Aktif</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-coffee-900 text-white text-xs font-mono font-bold" x-text="nextOrderNumber"></span>
                </div>

                <!-- Order Type (Dine In / Take Away) -->
                <div class="grid grid-cols-2 gap-1.5 p-1 rounded-xl bg-[#EFE8DC]/70 border border-[#E5DAC8] mb-3">
                    <button 
                        @click="orderType = 'dine_in'" 
                        :class="orderType === 'dine_in' ? 'bg-white text-coffee-950 font-bold shadow-sm' : 'text-coffee-600 font-semibold hover:text-coffee-900'"
                        class="py-1.5 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 01-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                        Dine In
                    </button>
                    <button 
                        @click="orderType = 'take_away'" 
                        :class="orderType === 'take_away' ? 'bg-white text-coffee-950 font-bold shadow-sm' : 'text-coffee-600 font-semibold hover:text-coffee-900'"
                        class="py-1.5 rounded-lg text-xs transition-all flex items-center justify-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Take Away
                    </button>
                </div>

                <!-- Customer Name Input -->
                <div class="relative">
                    <input 
                        type="text" 
                        x-model="customerName" 
                        placeholder="Nama Pelanggan (opsional)" 
                        class="w-full px-3.5 py-2 rounded-xl bg-white border border-[#E5DAC8] text-xs font-semibold text-coffee-950 placeholder-coffee-400 focus:outline-none focus:ring-2 focus:ring-caramel-500/40"
                    >
                </div>
            </div>

            <!-- Scrollable Cart Item List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3">
                <!-- Empty Cart State -->
                <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-center p-6 text-coffee-400" style="display: none;">
                    <div class="w-16 h-16 rounded-2xl bg-[#F8F5EE] border border-[#EFE8DE] flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-coffee-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <p class="font-bold text-coffee-800 text-sm">Keranjang masih kosong</p>
                    <p class="text-xs text-coffee-400 mt-1 max-w-[200px]">Klik item di katalog untuk memasukkan pesanan.</p>
                </div>

                <!-- Cart Items -->
                <template x-for="(item, index) in cart" :key="item.cart_id">
                    <div class="p-3.5 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] flex flex-col gap-2 hover:border-caramel-300/80 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="text-xs font-extrabold text-coffee-950 truncate" x-text="item.product.name"></h4>
                                
                                <!-- Temperature & Modifiers pill tags -->
                                <div class="flex flex-wrap items-center gap-1 mt-1">
                                    <template x-if="item.temperature">
                                        <span 
                                            class="px-1.5 py-0.5 rounded text-[10px] font-bold"
                                            :class="item.temperature === 'Ice' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-900'"
                                            x-text="item.temperature"
                                        ></span>
                                    </template>
                                    <template x-if="item.sugar_level">
                                        <span class="px-1.5 py-0.5 rounded bg-cream-200 text-coffee-800 text-[10px] font-medium" x-text="item.sugar_level"></span>
                                    </template>
                                    <template x-for="mod in item.modifiers" :key="mod.id">
                                        <span class="px-1.5 py-0.5 rounded bg-caramel-100 text-caramel-800 text-[10px] font-semibold" x-text="'+ ' + mod.name"></span>
                                    </template>
                                </div>

                                <template x-if="item.notes">
                                    <p class="text-[10px] text-coffee-500 italic mt-1" x-text="'Catatan: ' + item.notes"></p>
                                </template>
                            </div>

                            <!-- Delete Item Button -->
                            <button 
                                @click="removeFromCart(index)" 
                                class="text-coffee-300 hover:text-red-500 transition-colors p-1"
                                title="Hapus Item"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Price & Stepper Row -->
                        <div class="flex items-center justify-between pt-2 border-t border-[#F2ECE1]">
                            <span class="text-xs font-black text-coffee-900" x-text="formatRupiah(item.unit_price * item.quantity)"></span>

                            <!-- Stepper -->
                            <div class="flex items-center gap-2 bg-white rounded-xl border border-[#E5DAC8] p-1 shadow-sm">
                                <button 
                                    @click="decreaseQty(index)" 
                                    class="w-6 h-6 rounded-lg bg-[#FAF7F2] hover:bg-caramel-500 hover:text-white flex items-center justify-center text-coffee-800 text-xs font-bold transition-colors"
                                >-</button>
                                <span class="text-xs font-bold text-coffee-950 w-5 text-center" x-text="item.quantity"></span>
                                <button 
                                    @click="increaseQty(index)" 
                                    class="w-6 h-6 rounded-lg bg-[#FAF7F2] hover:bg-caramel-500 hover:text-white flex items-center justify-center text-coffee-800 text-xs font-bold transition-colors"
                                >+</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Calculation & Bottom CTA -->
            <div class="p-5 border-t border-[#EFE8DE] bg-[#FAF7F2] flex flex-col gap-3">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-coffee-600 font-medium">
                        <span>Subtotal</span>
                        <span class="font-bold text-coffee-900" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    
                    <div class="flex items-center justify-between text-coffee-600 font-medium">
                        <span class="flex items-center gap-1">
                            <span>Diskon</span>
                            <template x-if="discount > 0">
                                <span class="text-[10px] text-emerald-600 font-bold">(Hemat)</span>
                            </template>
                        </span>
                        <div class="flex items-center gap-1.5">
                            <input 
                                type="number" 
                                x-model.number="discount" 
                                min="0" 
                                step="1000" 
                                class="w-20 text-right px-2 py-0.5 text-xs bg-white border border-[#E5DAC8] rounded font-bold text-coffee-900 focus:outline-none"
                            >
                        </div>
                    </div>

                    <div class="flex justify-between text-coffee-600 font-medium">
                        <span>Pajak PPN (10%)</span>
                        <span class="font-bold text-coffee-900" x-text="formatRupiah(tax)"></span>
                    </div>

                    <div class="flex justify-between text-coffee-600 font-medium">
                        <span>Service Charge (2%)</span>
                        <span class="font-bold text-coffee-900" x-text="formatRupiah(serviceCharge)"></span>
                    </div>

                    <div class="flex justify-between items-baseline pt-2 border-t border-[#E5DAC8] text-sm">
                        <span class="font-extrabold text-coffee-950">TOTAL BAYAR</span>
                        <span class="text-xl font-black text-caramel-600" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <!-- Main CTA Button -->
                <div class="flex gap-2 pt-2">
                    <button 
                        @click="clearCart()" 
                        :disabled="cart.length === 0"
                        class="p-3 rounded-2xl border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                        title="Kosongkan Keranjang"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>

                    <button 
                        @click="openPaymentModal()" 
                        :disabled="cart.length === 0"
                        class="flex-1 py-3.5 px-5 rounded-2xl bg-gradient-to-r from-coffee-900 via-coffee-800 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-sm shadow-xl shadow-caramel-950/15 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                        <span>BAYAR SEKARANG</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SCREEN 6: MODAL KUSTOMISASI PESANAN (MODIFIER)                           -->
        <!-- ========================================================================= -->
        <div 
            x-show="modifierModal.show" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/60 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="modifierModal.show = false"
                class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-[#EAE2D5] transform transition-all"
            >
                <!-- Modal Header -->
                <div class="relative h-44 bg-coffee-900 overflow-hidden">
                    <img 
                        :src="modifierModal.product?.image || 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500'" 
                        :alt="modifierModal.product?.name"
                        class="w-full h-full object-cover opacity-60"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-coffee-950 via-coffee-950/40 to-transparent"></div>
                    
                    <button 
                        @click="modifierModal.show = false" 
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white hover:bg-white/30 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="absolute bottom-4 left-5 right-5 text-white">
                        <span class="text-xs font-bold uppercase tracking-wider text-caramel-400" x-text="modifierModal.product?.category?.name || 'Coffee'"></span>
                        <h3 class="text-xl font-black leading-tight" x-text="modifierModal.product?.name"></h3>
                        <p class="text-sm font-black text-caramel-300 mt-0.5" x-text="formatRupiah(modifierModal.product?.price)"></p>
                    </div>
                </div>

                <!-- Modal Body Options -->
                <div class="p-6 space-y-5 max-h-[60vh] overflow-y-auto">
                    
                    <!-- 1. Varian Suhu (Hot / Ice) -->
                    <template x-if="modifierModal.product?.has_temperature">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Varian Suhu</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label 
                                    class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                                    :class="modifierModal.selectedTemperature === 'Ice' ? 'border-sky-500 bg-sky-50/70 shadow-sm' : 'border-[#EAE2D5] hover:border-coffee-300'"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" value="Ice" x-model="modifierModal.selectedTemperature" class="text-sky-600 focus:ring-sky-500">
                                        <span class="font-bold text-sm text-coffee-950">Dingin / Ice</span>
                                    </div>
                                    <span class="text-xl">🧊</span>
                                </label>

                                <label 
                                    class="flex items-center justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
                                    :class="modifierModal.selectedTemperature === 'Hot' ? 'border-amber-600 bg-amber-50/70 shadow-sm' : 'border-[#EAE2D5] hover:border-coffee-300'"
                                >
                                    <div class="flex items-center gap-3">
                                        <input type="radio" value="Hot" x-model="modifierModal.selectedTemperature" class="text-amber-600 focus:ring-amber-500">
                                        <span class="font-bold text-sm text-coffee-950">Panas / Hot</span>
                                    </div>
                                    <span class="text-xl">☕</span>
                                </label>
                            </div>
                        </div>
                    </template>

                    <!-- 2. Sugar Level -->
                    <template x-if="modifierModal.product?.category?.slug === 'coffee' || modifierModal.product?.category?.slug === 'tea' || modifierModal.product?.category?.slug === 'non-coffee'">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Tingkat Kemanisan</label>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="sugar in ['Normal (100%)', 'Less Sugar (50%)', 'No Sugar (0%)']" :key="sugar">
                                    <button 
                                        type="button"
                                        @click="modifierModal.selectedSugar = sugar" 
                                        :class="modifierModal.selectedSugar === sugar ? 'bg-coffee-900 text-white font-bold border-coffee-900 shadow-sm' : 'bg-white text-coffee-700 border-[#EAE2D5] hover:bg-cream-100'"
                                        class="py-2.5 px-2 rounded-xl text-xs border text-center transition-all"
                                        x-text="sugar"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- 3. Toppings & Modifiers Selection -->
                    <template x-if="modifierModal.product?.modifiers && modifierModal.product.modifiers.length > 0">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Pilihan Tambahan / Topping</label>
                            <div class="space-y-2">
                                <template x-for="mod in modifierModal.product.modifiers" :key="mod.id">
                                    <label 
                                        class="flex items-center justify-between p-3 rounded-xl border cursor-pointer transition-all"
                                        :class="isModifierSelected(mod.id) ? 'border-caramel-500 bg-caramel-50/50 shadow-sm' : 'border-[#EAE2D5] hover:border-coffee-300'"
                                    >
                                        <div class="flex items-center gap-3">
                                            <input 
                                                type="checkbox" 
                                                :value="mod.id" 
                                                x-model="modifierModal.selectedModifiers"
                                                class="rounded text-caramel-600 focus:ring-caramel-500 w-4 h-4"
                                            >
                                            <span class="font-bold text-xs text-coffee-900" x-text="mod.name"></span>
                                        </div>
                                        <span class="text-xs font-bold text-caramel-600" x-text="'+ ' + formatRupiah(mod.price)"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- 4. Notes -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Catatan Khusus Barista</label>
                        <textarea 
                            x-model="modifierModal.notes" 
                            rows="2" 
                            placeholder="Contoh: jangan terlalu manis, pisahkan es batu..."
                            class="w-full p-3 rounded-xl border border-[#EAE2D5] text-xs font-medium text-coffee-950 placeholder-coffee-400 focus:outline-none focus:ring-2 focus:ring-caramel-500/40"
                        ></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-5 border-t border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-coffee-400 block">Total Item</span>
                        <span class="text-lg font-black text-coffee-900" x-text="formatRupiah(calculateModalTotal())"></span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button 
                            @click="modifierModal.show = false" 
                            class="px-4 py-2.5 rounded-xl border border-coffee-200 text-coffee-700 font-bold text-xs hover:bg-white"
                        >Batal</button>
                        <button 
                            @click="commitModifierToCart()" 
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-xs shadow-lg shadow-caramel-900/20"
                        >Tambah ke Pesanan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SCREEN 7: PAYMENT MODAL FLOW                                             -->
        <!-- ========================================================================= -->
        <div 
            x-show="paymentModal.show" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/60 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="if (!paymentModal.loading) paymentModal.show = false"
                class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-[#EAE2D5]"
            >
                <!-- Header -->
                <div class="p-6 pb-4 border-b border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-coffee-950">Pembayaran Kasir</h3>
                        <p class="text-xs font-semibold text-coffee-500" x-text="'Transaksi ' + nextOrderNumber + ' • ' + (customerName || 'Pelanggan Umum')"></p>
                    </div>
                    <button 
                        @click="paymentModal.show = false" 
                        class="w-8 h-8 rounded-full bg-cream-200 text-coffee-700 hover:bg-cream-300 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Grand Total Banner -->
                    <div class="p-5 rounded-2xl bg-coffee-950 text-white flex items-center justify-between shadow-inner">
                        <div>
                            <span class="text-xs font-bold text-caramel-400 uppercase tracking-widest block">Total Tagihan</span>
                            <span class="text-3xl font-black text-white" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                        <span class="px-3 py-1 rounded-lg bg-coffee-800 text-cream-100 text-xs font-bold border border-coffee-700" x-text="cart.length + ' Menu'"></span>
                    </div>

                    <!-- Payment Method Tabs -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-5 gap-2">
                            <template x-for="method in [
                                { id: 'cash', label: 'Tunai', icon: '💵' },
                                { id: 'qris', label: 'QRIS', icon: '📱' },
                                { id: 'debit', label: 'Debit', icon: '💳' },
                                { id: 'credit', label: 'Kredit', icon: '🏦' },
                                { id: 'ewallet', label: 'E-Wallet', icon: '⚡' }
                            ]" :key="method.id">
                                <button 
                                    type="button"
                                    @click="selectPaymentMethod(method.id)" 
                                    :class="paymentModal.method === method.id 
                                        ? 'border-caramel-500 bg-caramel-50/70 text-coffee-950 font-bold ring-2 ring-caramel-500/30' 
                                        : 'border-[#EAE2D5] bg-white text-coffee-700 hover:border-coffee-300'"
                                    class="p-3 rounded-2xl border text-center flex flex-col items-center gap-1 transition-all"
                                >
                                    <span class="text-lg" x-text="method.icon"></span>
                                    <span class="text-[11px]" x-text="method.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Mode 1: CASH -->
                    <div x-show="paymentModal.method === 'cash'" class="space-y-4">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Nominal Cepat (Quick Cash)</label>
                            <div class="grid grid-cols-4 gap-2">
                                <button 
                                    type="button" 
                                    @click="paymentModal.amount = grandTotal" 
                                    class="py-2.5 px-3 rounded-xl bg-cream-100 hover:bg-caramel-100 border border-[#EAE2D5] text-xs font-bold text-coffee-950 transition-colors"
                                >
                                    Uang Pas
                                </button>
                                <template x-for="nom in [20000, 50000, 100000, 200000]" :key="nom">
                                    <button 
                                        type="button" 
                                        @click="paymentModal.amount = nom" 
                                        class="py-2.5 px-3 rounded-xl bg-cream-100 hover:bg-caramel-100 border border-[#EAE2D5] text-xs font-bold text-coffee-950 transition-colors"
                                        x-text="formatRupiah(nom)"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-600 mb-2">Uang Diterima (Rp)</label>
                            <input 
                                type="number" 
                                x-model.number="paymentModal.amount" 
                                class="w-full px-4 py-3 rounded-xl border border-[#EAE2D5] text-xl font-black text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                                placeholder="Masukkan nominal tunai..."
                            >
                        </div>

                        <!-- Change calculation -->
                        <div class="p-4 rounded-2xl bg-[#FAF7F2] border border-[#EAE2D5] flex items-center justify-between">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-coffee-600">Kembalian</span>
                            <span 
                                class="text-xl font-black"
                                :class="paymentModal.amount >= grandTotal ? 'text-emerald-700' : 'text-red-600'"
                                x-text="paymentModal.amount >= grandTotal ? formatRupiah(paymentModal.amount - grandTotal) : 'Kurang ' + formatRupiah(grandTotal - paymentModal.amount)"
                            ></span>
                        </div>
                    </div>

                    <!-- Mode 2: QRIS -->
                    <div x-show="paymentModal.method === 'qris'" class="text-center p-4 border border-dashed border-[#DACFBE] rounded-2xl bg-[#FCFAF6] space-y-3">
                        <div class="inline-block p-3 rounded-2xl bg-white border border-[#EAE2D5] shadow-md">
                            <!-- Simulated SVG QR Code -->
                            <svg class="w-44 h-44 text-coffee-950" viewBox="0 0 100 100" fill="currentColor">
                                <path d="M0 0h30v30H0zM10 10h10v10H10zM70 0h30v30H70zM80 10h10v10H80zM0 70h30v30H0zM10 80h10v10H10zM40 10h10v20H40zM50 40h20v10H50zM10 40h20v10H10zM40 70h10v20H40zM60 70h30v10H60zM70 50h20v10H70zM40 50h10v10H40zM80 80h10v10H80z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-extrabold text-sm text-coffee-950">QRIS Dinamis Kopi Senja</p>
                            <p class="text-xs text-coffee-500">NMID: ID1029384759 • PT Kopi Senja Indonesia</p>
                            <p class="text-xs text-caramel-600 font-bold mt-1">Scan melalui BCA, GoPay, OVO, Dana, atau ShopeePay</p>
                        </div>
                    </div>

                    <!-- Mode 3: EDC / Cards / E-Wallet -->
                    <div x-show="['debit', 'credit', 'ewallet'].includes(paymentModal.method)" class="p-5 border border-[#EAE2D5] rounded-2xl bg-[#FAF7F2] text-center space-y-2">
                        <span class="text-3xl">💳</span>
                        <h4 class="font-bold text-coffee-950 text-sm">Gunakan Mesin EDC / Reader</h4>
                        <p class="text-xs text-coffee-500">Silakan tap kartu atau scan pada mesin EDC POS Kasir.</p>
                    </div>
                </div>

                <!-- Payment Modal Footer -->
                <div class="p-5 border-t border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-end gap-3">
                    <button 
                        @click="paymentModal.show = false" 
                        class="px-5 py-3 rounded-xl border border-coffee-200 text-coffee-700 font-bold text-xs hover:bg-white"
                        :disabled="paymentModal.loading"
                    >Batal</button>
                    
                    <button 
                        @click="processCheckout()" 
                        :disabled="paymentModal.loading || (paymentModal.method === 'cash' && paymentModal.amount < grandTotal)"
                        class="px-7 py-3 rounded-xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-xs shadow-xl shadow-caramel-900/20 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2 cursor-pointer"
                    >
                        <template x-if="paymentModal.loading">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </template>
                        <span x-text="paymentModal.loading ? 'Memproses...' : 'Konfirmasi & Selesaikan'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SUCCESS MODAL & THERMAL RECEIPT PREVIEW                                  -->
        <!-- ========================================================================= -->
        <div 
            x-show="successModal.show" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/70 backdrop-blur-md"
            style="display: none;"
        >
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#EAE2D5] text-center space-y-5">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <div>
                    <h3 class="text-xl font-black text-coffee-950">Pembayaran Berhasil!</h3>
                    <p class="text-xs text-coffee-500 mt-1">Transaksi telah tersimpan dan pesanan sedang disiapkan.</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-lg bg-[#FAF7F2] border border-[#EAE2D5] font-mono font-bold text-xs text-coffee-900" x-text="successModal.order?.order_number"></span>
                </div>

                <div class="p-3.5 rounded-2xl bg-[#FCFAF6] border border-[#EFE8DE] text-left text-xs space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-coffee-500">Metode:</span>
                        <span class="font-bold text-coffee-900 uppercase" x-text="successModal.order?.payment_method"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-coffee-500">Total Bayar:</span>
                        <span class="font-black text-coffee-900" x-text="formatRupiah(successModal.order?.total)"></span>
                    </div>
                    <template x-if="successModal.order?.payment_method === 'cash'">
                        <div class="flex justify-between">
                            <span class="text-coffee-500">Kembalian:</span>
                            <span class="font-bold text-emerald-700" x-text="formatRupiah(successModal.order?.change_amount)"></span>
                        </div>
                    </template>
                </div>

                <div class="flex flex-col gap-2 pt-2">
                    <a 
                        :href="successModal.receiptUrl" 
                        target="_blank"
                        class="w-full py-3 rounded-xl bg-coffee-900 hover:bg-coffee-950 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-lg"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Struk Thermal
                    </a>

                    <button 
                        @click="startNewOrder()" 
                        class="w-full py-3 rounded-xl border border-coffee-200 hover:bg-cream-100 text-coffee-800 font-extrabold text-xs"
                    >
                        + Pesanan Baru
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- POS Alpine Component Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', (config) => ({
                categories: config.categories,
                products: config.products,
                nextOrderNumber: config.nextOrderNumber,
                csrfToken: config.csrfToken,
                checkoutUrl: config.checkoutUrl,

                searchQuery: '',
                selectedCategory: 'all',
                orderType: 'dine_in',
                customerName: '',
                cart: [],
                discount: 0,

                // Modifier Modal State
                modifierModal: {
                    show: false,
                    product: null,
                    selectedTemperature: 'Ice',
                    selectedSugar: 'Normal (100%)',
                    selectedModifiers: [],
                    notes: ''
                },

                // Payment Modal State
                paymentModal: {
                    show: false,
                    method: 'cash',
                    amount: 0,
                    loading: false
                },

                // Success Modal State
                successModal: {
                    show: false,
                    order: null,
                    receiptUrl: ''
                },

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchCat = this.selectedCategory === 'all' || p.category_id === this.selectedCategory;
                        const matchQuery = !this.searchQuery || 
                            p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                            (p.description && p.description.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        return matchCat && matchQuery;
                    });
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
                },

                get taxable() {
                    return Math.max(0, this.subtotal - (this.discount || 0));
                },

                get tax() {
                    return Math.round(this.taxable * 0.10);
                },

                get serviceCharge() {
                    return Math.round(this.taxable * 0.02);
                },

                get grandTotal() {
                    return this.taxable + this.tax + this.serviceCharge;
                },

                handleProductClick(product) {
                    if (product.has_temperature || (product.modifiers && product.modifiers.length > 0)) {
                        this.openModifierModal(product);
                    } else {
                        this.addToCartDirect(product);
                    }
                },

                addToCartDirect(product) {
                    const existingIndex = this.cart.findIndex(item => 
                        item.product.id === product.id && 
                        !item.temperature && 
                        item.modifiers.length === 0
                    );

                    if (existingIndex > -1) {
                        this.cart[existingIndex].quantity += 1;
                    } else {
                        this.cart.push({
                            cart_id: Date.now() + Math.random(),
                            product: product,
                            unit_price: Number(product.price),
                            quantity: 1,
                            temperature: null,
                            sugar_level: null,
                            modifiers: [],
                            notes: ''
                        });
                    }
                },

                openModifierModal(product) {
                    this.modifierModal.product = product;
                    this.modifierModal.selectedTemperature = product.has_temperature ? 'Ice' : null;
                    this.modifierModal.selectedSugar = 'Normal (100%)';
                    this.modifierModal.selectedModifiers = [];
                    this.modifierModal.notes = '';
                    this.modifierModal.show = true;
                },

                isModifierSelected(id) {
                    return this.modifierModal.selectedModifiers.includes(id);
                },

                calculateModalTotal() {
                    if (!this.modifierModal.product) return 0;
                    let total = Number(this.modifierModal.product.price);
                    if (this.modifierModal.product.modifiers) {
                        this.modifierModal.product.modifiers.forEach(mod => {
                            if (this.modifierModal.selectedModifiers.includes(mod.id)) {
                                total += Number(mod.price);
                            }
                        });
                    }
                    return total;
                },

                commitModifierToCart() {
                    const product = this.modifierModal.product;
                    const selectedMods = (product.modifiers || []).filter(mod => 
                        this.modifierModal.selectedModifiers.includes(mod.id)
                    );
                    const unitPrice = this.calculateModalTotal();

                    this.cart.push({
                        cart_id: Date.now() + Math.random(),
                        product: product,
                        unit_price: unitPrice,
                        quantity: 1,
                        temperature: this.modifierModal.selectedTemperature,
                        sugar_level: this.modifierModal.selectedSugar,
                        modifiers: selectedMods,
                        notes: this.modifierModal.notes
                    });

                    this.modifierModal.show = false;
                },

                increaseQty(index) {
                    this.cart[index].quantity += 1;
                },

                decreaseQty(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity -= 1;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    if (confirm('Kosongkan semua pesanan di keranjang?')) {
                        this.cart = [];
                        this.discount = 0;
                    }
                },

                openPaymentModal() {
                    if (this.cart.length === 0) return;
                    this.paymentModal.amount = this.grandTotal;
                    this.paymentModal.method = 'cash';
                    this.paymentModal.loading = false;
                    this.paymentModal.show = true;
                },

                selectPaymentMethod(method) {
                    this.paymentModal.method = method;
                    if (method !== 'cash') {
                        this.paymentModal.amount = this.grandTotal;
                    }
                },

                async processCheckout() {
                    this.paymentModal.loading = true;

                    const payload = {
                        customer_name: this.customerName,
                        order_type: this.orderType,
                        payment_method: this.paymentModal.method,
                        payment_amount: this.paymentModal.amount,
                        discount: this.discount || 0,
                        notes: '',
                        items: this.cart.map(item => ({
                            product_id: item.product.id,
                            quantity: item.quantity,
                            temperature: item.temperature,
                            modifiers: item.modifiers.map(m => m.id),
                            notes: (item.sugar_level ? item.sugar_level + '. ' : '') + (item.notes || '')
                        }))
                    };

                    try {
                        const res = await fetch(this.checkoutUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Terjadi kesalahan transaksi.');
                            alert(errorMsg);
                            this.paymentModal.loading = false;
                            return;
                        }

                        // Success
                        this.paymentModal.show = false;
                        this.successModal.order = data.order;
                        this.successModal.receiptUrl = data.receipt_url;
                        this.successModal.show = true;

                    } catch (err) {
                        alert('Gagal terhubung ke server: ' + err.message);
                    } finally {
                        this.paymentModal.loading = false;
                    }
                },

                startNewOrder() {
                    this.cart = [];
                    this.discount = 0;
                    this.customerName = '';
                    this.successModal.show = false;
                    // Refresh order sequence number
                    const parts = this.nextOrderNumber.split('-');
                    if (parts.length === 3) {
                        const seq = parseInt(parts[2], 10) + 1;
                        this.nextOrderNumber = `${parts[0]}-${parts[1]}-${String(seq).padStart(4, '0')}`;
                    }
                }
            }));
        });
    </script>
</x-layouts.app>
