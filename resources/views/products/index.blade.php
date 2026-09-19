<x-layouts.app title="Manajemen Produk & Menu — Kopi Senja">
    <div class="p-8 max-w-7xl mx-auto w-full space-y-6" x-data="{
        addModal: false,
        editModal: {
            show: false,
            product: null,
            modifiers: []
        },
        openEdit(prod) {
            this.editModal.product = prod;
            this.editModal.modifiers = (prod.modifiers || []).map(m => m.id);
            this.editModal.show = true;
        },
        async toggleStatus(productId) {
            try {
                const res = await fetch(`/products/${productId}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch(e) {
                alert('Gagal mengubah status: ' + e.message);
            }
        }
    }">

        <!-- Top Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-coffee-950 tracking-tight">Manajemen Produk & Menu</h1>
                <p class="text-sm font-semibold text-coffee-600">Kelola katalog menu cafe, varian suhu (Hot/Ice), dan modifier topping.</p>
            </div>

            <button 
                @click="addModal = true"
                class="px-5 py-3 rounded-2xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-sm shadow-xl shadow-caramel-900/15 flex items-center gap-2 transition-all cursor-pointer self-start"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Menu Baru</span>
            </button>
        </div>

        <!-- 3 Quick Counter Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Total Menu</span>
                    <span class="text-2xl font-black text-coffee-950 mt-1 block">{{ $totalProductsCount }} Item</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-coffee-100 text-coffee-800 flex items-center justify-center font-bold">
                    ☕
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Menu Tersedia</span>
                    <span class="text-2xl font-black text-emerald-700 mt-1 block">{{ $availableCount }} Item</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                    ✓
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-[#EAE2D5] shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-coffee-500 uppercase tracking-wider block">Stok Habis / Nonaktif</span>
                    <span class="text-2xl font-black text-rose-700 mt-1 block">{{ $outOfStockCount }} Item</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center font-bold">
                    ✕
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-3xl p-5 border border-[#EAE2D5] shadow-sm">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 justify-between">
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-coffee-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari menu kopi, snack, deskripsi..." 
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-semibold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- Category Filter -->
                    <select name="category_id" class="py-2 px-3 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-bold text-coffee-800 focus:outline-none">
                        <option value="all" {{ request('category_id') === 'all' ? 'selected' : '' }}>Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="py-2 px-3 rounded-xl bg-[#FAF7F2] border border-[#E5DAC8] text-xs font-bold text-coffee-800 focus:outline-none">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Habis</option>
                    </select>

                    <button type="submit" class="py-2 px-4 rounded-xl bg-coffee-900 text-white font-bold text-xs hover:bg-coffee-950 transition-colors">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'category_id', 'status']))
                        <a href="{{ route('products.index') }}" class="py-2 px-3 rounded-xl border border-coffee-200 text-coffee-600 text-xs font-bold hover:bg-cream-100">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Products Table Card -->
        <div class="bg-white rounded-3xl border border-[#EAE2D5] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#FAF7F2] border-b border-[#EAE2D5] text-coffee-500 font-extrabold uppercase tracking-wider">
                            <th class="py-4 px-6">Produk</th>
                            <th class="py-4 px-4">Kategori</th>
                            <th class="py-4 px-4">Harga Jual</th>
                            <th class="py-4 px-4">Varian & Modifiers</th>
                            <th class="py-4 px-4">Status Ketersediaan</th>
                            <th class="py-4 px-4">Terakhir Diubah</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F2ECE1]">
                        @forelse($products as $product)
                            <tr class="hover:bg-[#FCFAF6] transition-colors">
                                <!-- Product Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <img 
                                            src="{{ $product->image ?: 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=200' }}" 
                                            alt="{{ $product->name }}" 
                                            class="w-12 h-12 rounded-xl object-cover border border-[#E5DAC8] shrink-0"
                                        >
                                        <div class="min-w-0">
                                            <h4 class="font-extrabold text-sm text-coffee-950">{{ $product->name }}</h4>
                                            <p class="text-[11px] text-coffee-500 line-clamp-1 max-w-xs">{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-cream-100 text-coffee-800 text-[11px] font-extrabold border border-[#EAE2D5]">
                                        {{ $product->category->name ?? 'Cafe' }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="py-4 px-4 font-black text-coffee-950 text-sm whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                <!-- Modifiers & Temperature -->
                                <td class="py-4 px-4 max-w-xs">
                                    <div class="flex flex-wrap gap-1">
                                        @if($product->has_temperature)
                                            <span class="px-1.5 py-0.5 rounded bg-sky-100 text-sky-800 text-[10px] font-bold">Hot / Ice</span>
                                        @endif
                                        @forelse($product->modifiers as $mod)
                                            <span class="px-1.5 py-0.5 rounded bg-caramel-100 text-caramel-800 text-[10px] font-medium">+ {{ $mod->name }}</span>
                                        @empty
                                            @if(!$product->has_temperature)
                                                <span class="text-coffee-400 text-[11px] italic">Standar (Tanpa Modifier)</span>
                                            @endif
                                        @endforelse
                                    </div>
                                </td>

                                <!-- Availability Toggle -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <button 
                                        type="button"
                                        @click="toggleStatus({{ $product->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all cursor-pointer
                                            {{ $product->is_available ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}"
                                        title="Klik untuk mengubah status"
                                    >
                                        <span class="w-2 h-2 rounded-full {{ $product->is_available ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span>{{ $product->is_available ? 'Tersedia' : 'Habis' }}</span>
                                    </button>
                                </td>

                                <!-- Updated -->
                                <td class="py-4 px-4 text-coffee-500 text-[11px] font-medium whitespace-nowrap">
                                    {{ $product->updated_at->diffForHumans() }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <button 
                                            @click="openEdit({{ Js::from($product) }})"
                                            class="px-2.5 py-1.5 rounded-lg bg-cream-100 hover:bg-caramel-100 text-coffee-800 font-bold text-xs transition-colors"
                                        >
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Hapus menu {{ $product->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="p-1.5 rounded-lg text-coffee-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                title="Hapus Menu"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-coffee-400 font-bold">
                                    Tidak ada produk yang sesuai kriteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-[#EAE2D5] bg-[#FAF7F2]">
                {{ $products->links() }}
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- SCREEN 5: MODAL TAMBAH MENU BARU (VARIAN SUHU & TOPPING)                  -->
        <!-- ========================================================================= -->
        <div 
            x-show="addModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/60 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="addModal = false"
                class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-[#EAE2D5]"
            >
                <!-- Modal Header -->
                <div class="p-6 pb-4 border-b border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-coffee-950">Tambah Menu Baru</h3>
                        <p class="text-xs font-semibold text-coffee-500">Konfigurasi produk, varian suhu, dan pilihan topping.</p>
                    </div>
                    <button 
                        @click="addModal = false" 
                        class="w-8 h-8 rounded-full bg-cream-200 text-coffee-700 hover:bg-cream-300 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form method="POST" action="{{ route('products.store') }}" class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    @csrf

                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Nama Produk *</label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            placeholder="Contoh: Es Kopi Susu Senja" 
                            class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-semibold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                        >
                    </div>

                    <!-- Category & Price Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Kategori *</label>
                            <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-bold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Harga Jual (Rp) *</label>
                            <input 
                                type="number" 
                                name="price" 
                                required 
                                min="0" 
                                step="500" 
                                placeholder="18000" 
                                class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-bold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                            >
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Deskripsi Menu</label>
                        <textarea 
                            name="description" 
                            rows="2" 
                            placeholder="Jelaskan cita rasa atau komposisi bahan..." 
                            class="w-full px-4 py-2 rounded-xl border border-[#E5DAC8] text-xs font-medium text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                        ></textarea>
                    </div>

                    <!-- Foto Produk URL -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">URL Foto Produk</label>
                        <input 
                            type="url" 
                            name="image" 
                            placeholder="https://images.unsplash.com/..." 
                            value="https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500"
                            class="w-full px-4 py-2 rounded-xl border border-[#E5DAC8] text-xs text-coffee-950 font-mono focus:outline-none focus:ring-2 focus:ring-caramel-500"
                        >
                    </div>

                    <!-- Varian Suhu (Hot / Ice) -->
                    <div class="p-4 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] space-y-2">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-800">Pilihan Varian Suhu</label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-coffee-900">
                                <input type="checkbox" name="has_temperature" value="1" checked class="rounded text-caramel-600 focus:ring-caramel-500 w-4 h-4">
                                <span>Aktifkan Opsi Suhu (Hot & Ice)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Topping & Modifiers (Multi-select) -->
                    <div class="p-4 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] space-y-2">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-800">Topping & Modifiers Tersedia</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($modifiers as $mod)
                                <label class="flex items-center justify-between p-2 rounded-xl border border-[#EAE2D5] bg-white hover:border-caramel-400 cursor-pointer text-xs">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="modifiers[]" value="{{ $mod->id }}" class="rounded text-caramel-600 focus:ring-caramel-500">
                                        <span class="font-bold text-coffee-900">{{ $mod->name }}</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-caramel-600">+Rp {{ number_format($mod->price, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Status Ketersediaan -->
                    <div class="flex items-center justify-between pt-2">
                        <div>
                            <span class="text-xs font-extrabold text-coffee-900 block">Status Ketersediaan Langsung</span>
                            <span class="text-[11px] text-coffee-500">Tampilkan langsung pada katalog kasir POS</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-coffee-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-coffee-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- Modal Actions -->
                    <div class="p-4 border-t border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-end gap-2 -mx-6 -mb-6 mt-4">
                        <button 
                            type="button" 
                            @click="addModal = false" 
                            class="px-5 py-2.5 rounded-xl border border-coffee-200 text-coffee-700 font-bold text-xs hover:bg-white"
                        >Batal</button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-xs shadow-lg shadow-caramel-900/20"
                        >Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODAL EDIT MENU                                                           -->
        <!-- ========================================================================= -->
        <div 
            x-show="editModal.show" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-coffee-950/60 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="editModal.show = false"
                class="bg-white rounded-3xl max-w-xl w-full overflow-hidden shadow-2xl border border-[#EAE2D5]"
            >
                <div class="p-6 pb-4 border-b border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-coffee-950">Edit Menu</h3>
                        <p class="text-xs font-semibold text-coffee-500">Perbarui detail produk, harga, atau varian.</p>
                    </div>
                    <button 
                        @click="editModal.show = false" 
                        class="w-8 h-8 rounded-full bg-cream-200 text-coffee-700 hover:bg-cream-300 flex items-center justify-center"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <template x-if="editModal.product">
                    <form 
                        method="POST" 
                        :action="`/products/${editModal.product.id}`" 
                        class="p-6 space-y-4 max-h-[70vh] overflow-y-auto"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Nama Produk *</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="editModal.product.name" 
                                required 
                                class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-semibold text-coffee-950 focus:outline-none focus:ring-2 focus:ring-caramel-500"
                            >
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Kategori *</label>
                                <select name="category_id" x-model="editModal.product.category_id" required class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-bold text-coffee-950">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Harga Jual (Rp) *</label>
                                <input 
                                    type="number" 
                                    name="price" 
                                    x-model="editModal.product.price" 
                                    required 
                                    min="0" 
                                    step="500" 
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#E5DAC8] text-xs font-bold text-coffee-950"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">Deskripsi Menu</label>
                            <textarea 
                                name="description" 
                                x-model="editModal.product.description" 
                                rows="2" 
                                class="w-full px-4 py-2 rounded-xl border border-[#E5DAC8] text-xs font-medium text-coffee-950"
                            ></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-700 mb-1">URL Foto Produk</label>
                            <input 
                                type="url" 
                                name="image" 
                                x-model="editModal.product.image" 
                                class="w-full px-4 py-2 rounded-xl border border-[#E5DAC8] text-xs text-coffee-950 font-mono"
                            >
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] space-y-2">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-800">Pilihan Varian Suhu</label>
                            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-coffee-900">
                                <input type="checkbox" name="has_temperature" value="1" x-model="editModal.product.has_temperature" class="rounded text-caramel-600 w-4 h-4">
                                <span>Aktifkan Opsi Suhu (Hot & Ice)</span>
                            </label>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FCFAF6] border border-[#EFE7DC] space-y-2">
                            <label class="block text-xs font-extrabold uppercase tracking-wider text-coffee-800">Topping & Modifiers Tersedia</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($modifiers as $mod)
                                    <label class="flex items-center justify-between p-2 rounded-xl border border-[#EAE2D5] bg-white hover:border-caramel-400 cursor-pointer text-xs">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="checkbox" 
                                                name="modifiers[]" 
                                                value="{{ $mod->id }}" 
                                                :checked="editModal.modifiers.includes({{ $mod->id }})"
                                                class="rounded text-caramel-600"
                                            >
                                            <span class="font-bold text-coffee-900">{{ $mod->name }}</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-caramel-600">+Rp {{ number_format($mod->price, 0, ',', '.') }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <div>
                                <span class="text-xs font-extrabold text-coffee-900 block">Status Ketersediaan</span>
                                <span class="text-[11px] text-coffee-500">Tersedia atau Habis</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" value="1" x-model="editModal.product.is_available" class="sr-only peer">
                                <div class="w-11 h-6 bg-coffee-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-coffee-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <div class="p-4 border-t border-[#EAE2D5] bg-[#FAF7F2] flex items-center justify-end gap-2 -mx-6 -mb-6 mt-4">
                            <button 
                                type="button" 
                                @click="editModal.show = false" 
                                class="px-5 py-2.5 rounded-xl border border-coffee-200 text-coffee-700 font-bold text-xs hover:bg-white"
                            >Batal</button>
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-coffee-900 to-caramel-600 hover:from-coffee-950 hover:to-caramel-700 text-white font-extrabold text-xs shadow-lg"
                            >Simpan Perubahan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

    </div>
</x-layouts.app>
