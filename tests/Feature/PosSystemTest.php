<?php

use App\Models\Category;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Ensure default cashier exists
    $this->cashier = User::firstOrCreate(
        ['email' => 'kasir@kopisenja.com'],
        ['name' => 'Rian Kasir', 'password' => 'password']
    );

    $this->category = Category::firstOrCreate(
        ['slug' => 'coffee'],
        ['name' => 'Coffee', 'icon' => 'coffee', 'is_active' => true]
    );

    $this->modifier = Modifier::firstOrCreate(
        ['name' => 'Extra Shot'],
        ['group' => 'topping', 'price' => 5000, 'is_active' => true]
    );

    $this->product = Product::firstOrCreate(
        ['slug' => 'es-kopi-susu-senja'],
        [
            'category_id' => $this->category->id,
            'name' => 'Es Kopi Susu Senja',
            'price' => 18000,
            'has_temperature' => true,
            'is_available' => true,
        ]
    );
});

test('pos page renders with categories and products', function () {
    $response = $this->get('/pos');
    $response->assertStatus(200);
    $response->assertSee('Katalog Menu');
    $response->assertSee('Es Kopi Susu Senja');
});

test('dashboard page renders with statistics and charts', function () {
    $response = $this->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Dashboard Ringkas');
    $response->assertSee('Penjualan Hari Ini');
});

test('transactions page renders order history', function () {
    $response = $this->get('/transactions');
    $response->assertStatus(200);
    $response->assertSee('Riwayat Transaksi');
});

test('products page renders and allows toggling status', function () {
    $response = $this->get('/products');
    $response->assertStatus(200);
    $response->assertSee('Manajemen Produk & Menu');

    $toggleResponse = $this->patch("/products/{$this->product->id}/toggle");
    $toggleResponse->assertRedirect();
    $this->product->refresh();
    expect($this->product->is_available)->toBeFalse();

    // Toggle back
    $this->patch("/products/{$this->product->id}/toggle");
    $this->product->refresh();
    expect($this->product->is_available)->toBeTrue();
});

test('checkout processes order with server-side price calculation', function () {
    $payload = [
        'customer_name' => 'Ahmad Fauzi',
        'order_type' => 'dine_in',
        'payment_method' => 'cash',
        'payment_amount' => 50000,
        'discount' => 0,
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'temperature' => 'Ice',
                'modifiers' => [$this->modifier->id],
                'notes' => 'Less sugar',
            ],
        ],
    ];

    $response = $this->postJson('/pos/checkout', $payload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $order = Order::where('customer_name', 'Ahmad Fauzi')->latest()->first();
    expect($order)->not->toBeNull();

    // Base 18000 + Modifier 5000 = 23000
    expect((float) $order->subtotal)->toBe(23000.0);
    // Tax 10% = 2300, Service 2% = 460, Total = 25760
    expect((float) $order->tax)->toBe(2300.0);
    expect((float) $order->service_charge)->toBe(460.0);
    expect((float) $order->total)->toBe(25760.0);
    expect((float) $order->change_amount)->toBe(50000 - 25760.0);

    expect($order->items)->toHaveCount(1);
    expect($order->items->first()->modifiers)->toHaveCount(1);
});

test('checkout rejects underpayment for cash', function () {
    $payload = [
        'customer_name' => 'Budi',
        'order_type' => 'take_away',
        'payment_method' => 'cash',
        'payment_amount' => 10000, // Less than minimum item price
        'discount' => 0,
        'items' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'temperature' => 'Hot',
            ],
        ],
    ];

    $response = $this->postJson('/pos/checkout', $payload);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['payment_amount']);
});

test('receipt page renders accurately', function () {
    $order = Order::create([
        'order_number' => 'KS-20260919-9999',
        'user_id' => $this->cashier->id,
        'customer_name' => 'Test Customer',
        'order_type' => 'dine_in',
        'subtotal' => 18000,
        'discount' => 0,
        'tax' => 1800,
        'service_charge' => 360,
        'total' => 20160,
        'payment_method' => 'cash',
        'payment_amount' => 50000,
        'change_amount' => 29840,
        'status' => 'completed',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'quantity' => 1,
        'unit_price' => 18000,
        'subtotal' => 18000,
    ]);

    $response = $this->get("/transactions/{$order->id}/receipt");
    $response->assertStatus(200);
    $response->assertSee('KOPI SENJA');
    $response->assertSee($order->order_number);
});
