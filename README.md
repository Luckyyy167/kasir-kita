# ☕ Kopi Senja POS

**Kopi Senja POS** adalah aplikasi **Point of Sale (POS) / Kasir Cafe berbasis Laravel** yang dirancang untuk membantu proses transaksi, pengelolaan menu, modifier/topping, pembayaran, dan monitoring penjualan secara terintegrasi.

Aplikasi ini menggunakan desain UI yang mengacu pada project **Google Stitch – Kopi Senja POS Dashboard** dan dikembangkan menjadi aplikasi Laravel yang fungsional serta terhubung dengan database.

---

## 📌 Project Information

| Informasi     | Detail                 |
| ------------- | ---------------------- |
| Nama Aplikasi | Kopi Senja POS         |
| Platform      | Web Application        |
| Framework     | Laravel                |
| Database      | MySQL / MariaDB        |
| UI            | Blade + Tailwind CSS   |
| JavaScript    | Alpine.js / JavaScript |
| Tema          | Cafe Point of Sale     |
| Status        | Development            |

### Referensi Google Stitch

**Project:** Kopi Senja POS Dashboard

**Project ID:**

```text
13723952864103222521
```

### Screen References

| No | Screen                    | ID                                 |
| -- | ------------------------- | ---------------------------------- |
| 1  | Kasir / POS               | `d92d185061e7468b9ec1a2019b260ab0` |
| 2  | Dashboard Ringkas         | `ff94b905840c4ebfaceacd88658689b9` |
| 3  | Riwayat Transaksi         | `ef8eee5386ed4509bf2707b8335c4d5e` |
| 4  | Manajemen Produk & Menu   | `7f83ed5723404301bf68e63b71b823c6` |
| 5  | Modal Tambah Menu Baru    | `6e60572dd51f4821b1a0104b18598a52` |
| 6  | Modal Kustomisasi Pesanan | `b1dcfb4073ba4e7ab167abe0b4d5018c` |

---

# ✨ Fitur Utama

## 1. Dashboard

Dashboard memberikan ringkasan aktivitas cafe.

Fitur:

* Penjualan hari ini
* Total transaksi
* Produk terjual
* Rata-rata transaksi
* Grafik penjualan
* Produk terlaris
* Transaksi terbaru
* Status toko

---

## 2. Kasir / POS

Halaman utama untuk melakukan transaksi.

Fitur:

* Pencarian produk
* Filter berdasarkan kategori
* Product card
* Tambah produk ke keranjang
* Update quantity
* Hapus produk
* Modifier produk
* Topping
* Varian suhu
* Diskon
* Pajak
* Service charge
* Perhitungan total otomatis
* Pembayaran
* Kembalian
* Penyelesaian transaksi

Flow transaksi:

```text
Pilih Produk
     ↓
Pilih Modifier
     ↓
Tambah ke Keranjang
     ↓
Review Pesanan
     ↓
Bayar
     ↓
Konfirmasi Pembayaran
     ↓
Transaksi Berhasil
     ↓
Cetak / Lihat Struk
```

---

# 🛒 Modifier Produk

Produk tertentu dapat memiliki modifier.

Contoh produk:

```text
Es Kopi Susu
```

Modifier:

```text
Suhu
├── Hot
└── Ice

Topping
├── Extra Shot
├── Caramel
├── Vanilla
├── Cream Cheese
└── Boba
```

Modifier dapat memiliki harga tambahan.

Contoh:

```text
Extra Shot     + Rp5.000
Caramel        + Rp3.000
Vanilla        + Rp3.000
```

Harga akhir dihitung berdasarkan:

```text
Harga Produk
+
Harga Modifier
=
Harga Item
```

---

# 💳 Pembayaran

Kopi Senja POS mendukung beberapa metode pembayaran:

* Cash
* QRIS
* Debit
* Credit Card
* E-Wallet

Untuk pembayaran tunai, sistem menghitung kembalian secara otomatis.

Contoh:

```text
Total       : Rp54.000
Dibayar     : Rp100.000
Kembalian   : Rp46.000
```

---

# 📦 Manajemen Produk

Admin dapat mengelola menu cafe.

Fitur:

* Tambah produk
* Edit produk
* Hapus produk
* Upload gambar
* Ubah harga
* Ubah kategori
* Aktif/nonaktif produk
* Mengatur modifier
* Mengatur ketersediaan produk

Kategori default:

```text
Coffee
Non Coffee
Tea
Food
Snack
Dessert
```

---

# 🧾 Riwayat Transaksi

Menampilkan seluruh transaksi yang telah dilakukan.

Informasi:

* Nomor transaksi
* Tanggal
* Waktu
* Kasir
* Pelanggan
* Produk
* Total
* Metode pembayaran
* Status transaksi

Fitur pencarian dan filtering tersedia berdasarkan:

* Nomor transaksi
* Tanggal
* Kasir
* Metode pembayaran
* Status

---

# 🖨️ Struk Transaksi

Setelah pembayaran berhasil, sistem dapat menampilkan struk.

Contoh:

```text
================================
          KOPI SENJA
       Coffee & More
================================

Transaction:
KS-20260919-0001

Es Kopi Susu
2 x Rp18.000             Rp36.000

Croissant
1 x Rp18.000             Rp18.000

--------------------------------
Subtotal                 Rp54.000
Tax                       Rp5.400
--------------------------------
TOTAL                    Rp59.400

Payment                  CASH
Received                Rp100.000
Change                   Rp40.600

================================
          Terima Kasih!
================================
```

Format struk dapat disesuaikan dengan kebutuhan printer thermal.

---

# 🏗️ Arsitektur Aplikasi

Struktur utama aplikasi:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── PosController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   └── TransactionController.php
│   │
│   └── Requests/
│       ├── StoreProductRequest.php
│       └── StoreOrderRequest.php
│
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Modifier.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── OrderItemModifier.php
│
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    │
    ├── components/
    │   ├── sidebar.blade.php
    │   ├── header.blade.php
    │   ├── product-card.blade.php
    │   ├── category-tabs.blade.php
    │   ├── cart.blade.php
    │   ├── modal.blade.php
    │   ├── stat-card.blade.php
    │   └── badge.blade.php
    │
    ├── dashboard/
    ├── pos/
    ├── products/
    └── transactions/
```

---

# 🗄️ Database

Database minimal terdiri dari:

```text
users
categories
products
modifiers
product_modifier
orders
order_items
order_item_modifiers
```

## Relasi

```text
Category
   │
   └── Products
          │
          └── Modifiers


Order
   │
   └── Order Items
          │
          └── Item Modifiers
```

---

# 📊 Database Schema

## Categories

```text
id
name
slug
is_active
created_at
updated_at
```

## Products

```text
id
category_id
name
slug
description
price
image
is_available
created_at
updated_at
```

## Modifiers

```text
id
name
price
is_active
created_at
updated_at
```

## Product Modifier

```text
product_id
modifier_id
```

## Orders

```text
id
order_number
user_id
customer_name
subtotal
discount
tax
service_charge
total
payment_method
payment_amount
change_amount
status
created_at
updated_at
```

## Order Items

```text
id
order_id
product_id
product_name
quantity
unit_price
subtotal
created_at
updated_at
```

## Order Item Modifiers

```text
id
order_item_id
modifier_id
modifier_name
price
created_at
updated_at
```

---

# 🔗 Eloquent Relationship

## Category

```php
public function products()
{
    return $this->hasMany(Product::class);
}
```

## Product

```php
public function category()
{
    return $this->belongsTo(Category::class);
}

public function modifiers()
{
    return $this->belongsToMany(Modifier::class);
}
```

## Order

```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function items()
{
    return $this->hasMany(OrderItem::class);
}
```

## OrderItem

```php
public function order()
{
    return $this->belongsTo(Order::class);
}

public function product()
{
    return $this->belongsTo(Product::class);
}

public function modifiers()
{
    return $this->hasMany(OrderItemModifier::class);
}
```

---

# 🛣️ Routes

Route utama:

```text
/dashboard
/pos
/products
/products/create
/products/{product}/edit
/transactions
/transactions/{order}
```

Contoh:

```php
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::get('/pos',
        [PosController::class, 'index']
    )->name('pos');

    Route::resource('products',
        ProductController::class
    );

    Route::get('/transactions',
        [TransactionController::class, 'index']
    )->name('transactions.index');

});
```

Sesuaikan route dengan struktur project yang digunakan.

---

# 🎨 UI / Design System

Kopi Senja POS menggunakan konsep:

* Modern
* Minimalis
* Premium
* Warm
* Clean
* Professional

Palet warna utama mengacu pada desain Stitch:

```text
Coffee Brown
Cream
Beige
White
Sage Accent
```

Komponen UI menggunakan:

* Rounded corners
* Soft shadow
* Clean typography
* Consistent spacing
* Modern icons
* Hover state
* Active state
* Loading state
* Empty state
* Error state
* Success state

---

# 📱 Responsive Design

Aplikasi dirancang untuk:

```text
Desktop
Tablet
Mobile
```

### Desktop

```text
┌─────────┬───────────────────────┬──────────────┐
│ Sidebar │     Product Area      │     Cart     │
│         │                       │              │
│         │                       │              │
└─────────┴───────────────────────┴──────────────┘
```

### Tablet

```text
┌─────────┬────────────────────────────┐
│ Sidebar │       Product Area         │
│         │                            │
└─────────┴────────────────────────────┘
```

### Mobile

```text
┌─────────────────────────┐
│ Header                  │
├─────────────────────────┤
│ Product                 │
│ Product                 │
│ Product                 │
├─────────────────────────┤
│ 🛒 Cart                 │
└─────────────────────────┘
```

---

# ⚙️ Requirements

Pastikan environment memiliki:

* PHP
* Composer
* Laravel
* Node.js
* NPM
* MySQL / MariaDB

Versi PHP dan Laravel mengikuti requirement pada `composer.json` project.

---

# 🚀 Installation

Clone project:

```bash
git clone <repository-url>
```

Masuk ke folder:

```bash
cd kopi-senja-pos
```

Install dependency Laravel:

```bash
composer install
```

Install frontend dependency:

```bash
npm install
```

---

# 🔐 Environment

Copy file `.env.example`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Kemudian konfigurasi database pada `.env`.

Contoh:

```env
APP_NAME="Kopi Senja POS"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kopi_senja
DB_USERNAME=root
DB_PASSWORD=
```

---

# 🗃️ Migration

Jalankan:

```bash
php artisan migrate
```

Jika membutuhkan data awal:

```bash
php artisan db:seed
```

atau:

```bash
php artisan migrate --seed
```

---

# 🧪 Testing

Jalankan test:

```bash
php artisan test
```

Pastikan minimal melakukan pengujian terhadap:

```text
✓ Login
✓ Dashboard
✓ Product CRUD
✓ Category
✓ Product Modifier
✓ Add to Cart
✓ Update Cart
✓ Remove Cart
✓ Payment
✓ Transaction
✓ Transaction History
```

---

# ▶️ Menjalankan Aplikasi

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

---

# 🧑‍💻 Development Workflow

Workflow transaksi:

```text
                 ┌──────────────┐
                 │    Kasir     │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │ Pilih Produk │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │   Modifier   │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │     Cart     │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │   Payment    │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │    Order     │
                 └──────┬───────┘
                        ↓
                 ┌──────────────┐
                 │   Receipt    │
                 └──────────────┘
```

---

# 🔒 Security

Aplikasi menerapkan prinsip keamanan Laravel:

* CSRF Protection
* Authentication
* Authorization
* Form Request Validation
* Server-side validation
* Eloquent ORM
* Database Transaction
* Mass assignment protection

**Jangan mempercayai nilai harga atau total yang dikirim dari browser.**

Harga produk dan modifier harus selalu diverifikasi kembali dari database sebelum transaksi disimpan.

---

# 💰 Perhitungan Transaksi

Perhitungan transaksi dilakukan di server.

Formula:

```text
Item Subtotal
=
Product Price × Quantity
+
Modifier Price
```

Kemudian:

```text
Subtotal
-
Discount
+
Tax
+
Service Charge
=
Grand Total
```

Untuk pembayaran tunai:

```text
Change
=
Payment Amount - Grand Total
```

Jika jumlah pembayaran kurang dari total:

```text
Payment Amount < Grand Total
```

maka transaksi tidak boleh diselesaikan.

---

# 🔢 Nomor Transaksi

Format nomor transaksi yang direkomendasikan:

```text
KS-YYYYMMDD-XXXX
```

Contoh:

```text
KS-20260919-0001
KS-20260919-0002
KS-20260919-0003
```

Nomor transaksi harus unik.

---

# 🧹 Coding Guidelines

Gunakan prinsip:

* DRY
* SOLID
* Separation of Concerns
* Reusable Components
* Clean Controller
* Form Request Validation
* Eloquent Relationship
* Service Layer jika business logic semakin kompleks

Hindari:

```text
❌ Business logic di Blade
❌ Hardcoded transaction
❌ Hardcoded total
❌ Duplicate component
❌ Query database berulang di view
❌ Inline SQL tanpa kebutuhan
```

---

# 📁 Asset

Asset UI disimpan secara terstruktur:

```text
public/
├── images/
│   ├── products/
│   └── logo/
│
├── icons/
└── favicon/
```

Untuk upload gambar produk gunakan Laravel Storage.

Contoh:

```bash
php artisan storage:link
```

---

# 🖥️ Halaman Aplikasi

| Halaman          | URL                   | Status      |
| ---------------- | --------------------- | ----------- |
| Dashboard        | `/dashboard`          | Development |
| POS / Kasir      | `/pos`                | Development |
| Produk           | `/products`           | Development |
| Tambah Produk    | `/products/create`    | Development |
| Edit Produk      | `/products/{id}/edit` | Development |
| Transaksi        | `/transactions`       | Development |
| Detail Transaksi | `/transactions/{id}`  | Development |

---

# 🧩 Komponen Utama

Komponen reusable:

```text
Sidebar
Header
Product Card
Category Tabs
Cart
Cart Item
Stat Card
Badge
Modal
Payment Modal
Modifier Modal
Product Form
Transaction Table
Toast Notification
Confirmation Dialog
```

---

# 📌 Roadmap

## Phase 1 — UI

* [x] Stitch design reference
* [ ] Dashboard UI
* [ ] POS UI
* [ ] Product Management UI
* [ ] Transaction UI
* [ ] Modifier Modal
* [ ] Payment Modal

## Phase 2 — Backend

* [ ] Database
* [ ] Models
* [ ] Migration
* [ ] Seeder
* [ ] Controllers
* [ ] Form Request
* [ ] Authentication
* [ ] Authorization

## Phase 3 — POS

* [ ] Product search
* [ ] Category filter
* [ ] Cart
* [ ] Modifier
* [ ] Quantity
* [ ] Discount
* [ ] Tax
* [ ] Payment
* [ ] Change calculation

## Phase 4 — Transaction

* [ ] Save order
* [ ] Transaction history
* [ ] Transaction detail
* [ ] Receipt
* [ ] Print receipt

## Phase 5 — Optimization

* [ ] Responsive optimization
* [ ] Performance optimization
* [ ] Query optimization
* [ ] Security audit
* [ ] Automated testing

---

# 🐛 Troubleshooting

## Clear Laravel Cache

```bash
php artisan optimize:clear
```

## Clear Config

```bash
php artisan config:clear
```

## Clear Route

```bash
php artisan route:clear
```

## Clear View

```bash
php artisan view:clear
```

## Rebuild Frontend

```bash
npm run build
```

---

# 📄 License

Project ini dikembangkan untuk kebutuhan internal **Kopi Senja POS**.

Hak penggunaan, distribusi, dan modifikasi mengikuti kebijakan pemilik project.

---

# 👨‍💻 Developer

**Kopi Senja POS**

Laravel-based Cafe Point of Sale System.

---

## ☕ Kopi Senja

> **Brew. Serve. Manage.**

Sistem kasir cafe yang sederhana, cepat, dan terintegrasi.
