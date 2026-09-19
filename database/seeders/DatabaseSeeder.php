<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Modifier;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Cashier & Admin)
        $cashier = User::firstOrCreate(
            ['email' => 'kasir@kopisenja.com'],
            [
                'name' => 'Rian Kasir',
                'password' => Hash::make('password'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@kopisenja.com'],
            [
                'name' => 'Manager Senja',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Seed Categories
        $categoriesData = [
            ['name' => 'Coffee', 'slug' => 'coffee', 'icon' => 'coffee'],
            ['name' => 'Non Coffee', 'slug' => 'non-coffee', 'icon' => 'cup-soda'],
            ['name' => 'Tea', 'slug' => 'tea', 'icon' => 'leaf'],
            ['name' => 'Food', 'slug' => 'food', 'icon' => 'utensils'],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => 'cookie'],
            ['name' => 'Dessert', 'slug' => 'dessert', 'icon' => 'cake'],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['slug']] = Category::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // 3. Seed Modifiers
        $modifiersData = [
            ['name' => 'Extra Shot', 'group' => 'topping', 'price' => 5000],
            ['name' => 'Caramel Sauce', 'group' => 'syrup', 'price' => 3000],
            ['name' => 'Vanilla Syrup', 'group' => 'syrup', 'price' => 3000],
            ['name' => 'Cream Cheese', 'group' => 'topping', 'price' => 5000],
            ['name' => 'Boba Pearls', 'group' => 'topping', 'price' => 4000],
            ['name' => 'Hazelnut Syrup', 'group' => 'syrup', 'price' => 3000],
            ['name' => 'Oat Milk Swap', 'group' => 'milk', 'price' => 6000],
        ];

        $modifiers = [];
        foreach ($modifiersData as $data) {
            $modifiers[$data['name']] = Modifier::firstOrCreate(['name' => $data['name']], $data);
        }

        // 4. Seed Products
        $productsData = [
            [
                'category' => 'coffee',
                'name' => 'Es Kopi Susu Senja',
                'slug' => 'es-kopi-susu-senja',
                'description' => 'Signature espresso blend dengan susu segar dan manis legit gula aren asli.',
                'price' => 18000,
                'image' => 'https://images.unsplash.com/photo-1517701550927-30cf4ba1dba5?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Extra Shot', 'Caramel Sauce', 'Cream Cheese', 'Boba Pearls', 'Vanilla Syrup'],
            ],
            [
                'category' => 'coffee',
                'name' => 'Caramel Macchiato',
                'slug' => 'caramel-macchiato',
                'description' => 'Perpaduan espresso kaya rasa, susu steamed vanila lembut, dan lelehan saus karamel.',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Extra Shot', 'Caramel Sauce', 'Oat Milk Swap', 'Cream Cheese'],
            ],
            [
                'category' => 'coffee',
                'name' => 'Cafe Latte',
                'slug' => 'cafe-latte',
                'description' => 'Espresso klasik yang dipadukan dengan microfoam susu creamy bertekstur sutra.',
                'price' => 24000,
                'image' => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Extra Shot', 'Vanilla Syrup', 'Hazelnut Syrup', 'Oat Milk Swap'],
            ],
            [
                'category' => 'coffee',
                'name' => 'Americano',
                'slug' => 'americano',
                'description' => 'Double shot espresso dipadukan dengan air panas atau es batu untuk cita rasa kopi murni.',
                'price' => 20000,
                'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Extra Shot', 'Caramel Sauce'],
            ],
            [
                'category' => 'coffee',
                'name' => 'Cappuccino',
                'slug' => 'cappuccino',
                'description' => 'Kombinasi seimbang espresso pekat dengan lapisan busa susu tebal bertabur bubuk cokelat.',
                'price' => 24000,
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Extra Shot', 'Hazelnut Syrup'],
            ],
            [
                'category' => 'non-coffee',
                'name' => 'Matcha Latte',
                'slug' => 'matcha-latte',
                'description' => 'Bubuk teh hijau matcha murni Uji Kyoto dipadukan dengan susu segar yang gurih.',
                'price' => 26000,
                'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Cream Cheese', 'Boba Pearls', 'Vanilla Syrup', 'Oat Milk Swap'],
            ],
            [
                'category' => 'non-coffee',
                'name' => 'Artisan Chocolate',
                'slug' => 'artisan-chocolate',
                'description' => 'Dark chocolate Belgia pilihan diseduh dengan susu bertekstur lembut dan manis pas.',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Cream Cheese', 'Boba Pearls', 'Hazelnut Syrup'],
            ],
            [
                'category' => 'non-coffee',
                'name' => 'Red Velvet Latte',
                'slug' => 'red-velvet-latte',
                'description' => 'Minuman manis red velvet dengan sentuhan aroma cokelat lembut dan gurihnya susu.',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1615478503562-ec2d8aa0e24e?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Cream Cheese', 'Boba Pearls'],
            ],
            [
                'category' => 'tea',
                'name' => 'Earl Grey Milk Tea',
                'slug' => 'earl-grey-milk-tea',
                'description' => 'Teh hitam premium beraroma minyak bergamot dengan paduan susu segar creamy.',
                'price' => 22000,
                'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Boba Pearls', 'Cream Cheese'],
            ],
            [
                'category' => 'tea',
                'name' => 'Lemon Jasmine Tea',
                'slug' => 'lemon-jasmine-tea',
                'description' => 'Teh melati wangi menyegarkan dengan perasan jeruk lemon asli dan es batu melimpah.',
                'price' => 20000,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => true,
                'is_available' => true,
                'modifiers' => ['Boba Pearls'],
            ],
            [
                'category' => 'food',
                'name' => 'Butter Croissant',
                'slug' => 'butter-croissant',
                'description' => 'Pastry ala Perancis dengan lapisan renyah buttery di luar dan lembut berongga di dalam.',
                'price' => 18000,
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
            [
                'category' => 'food',
                'name' => 'Smoked Beef Toast',
                'slug' => 'smoked-beef-toast',
                'description' => 'Roti brioche panggang berisi lembaran daging asap premium, keju leleh, dan scramble egg.',
                'price' => 28000,
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
            [
                'category' => 'snack',
                'name' => 'Truffle French Fries',
                'slug' => 'truffle-french-fries',
                'description' => 'Kentang goreng renyah bumbu truffle aromatik, taburan keju parmesan, dan saus mayo garlic.',
                'price' => 25000,
                'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
            [
                'category' => 'snack',
                'name' => 'Crispy Churros Cokelat',
                'slug' => 'crispy-churros-cokelat',
                'description' => 'Churros renyah bertabur cinnamon sugar wangi disajikan dengan saus cokelat lumer.',
                'price' => 22000,
                'image' => 'https://images.unsplash.com/photo-1624300629298-e9de39c13be5?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
            [
                'category' => 'dessert',
                'name' => 'New York Cheesecake',
                'slug' => 'new-york-cheesecake',
                'description' => 'Kue keju panggang klasik Amerika yang padat, creamy, dengan dasar biskuit mentega gurih.',
                'price' => 32000,
                'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
            [
                'category' => 'dessert',
                'name' => 'Fudgy Dark Brownie',
                'slug' => 'fudgy-dark-brownie',
                'description' => 'Brownie cokelat pekat bertekstur chewy fudgy dengan lapisan shiny crust di atasnya.',
                'price' => 20000,
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=500&auto=format&fit=crop&q=80',
                'has_temperature' => false,
                'is_available' => true,
                'modifiers' => [],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $cat = $categories[$data['category']];
            $prod = Product::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $cat->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'image' => $data['image'],
                    'has_temperature' => $data['has_temperature'],
                    'is_available' => $data['is_available'],
                ]
            );

            if (! empty($data['modifiers'])) {
                $modifierIds = [];
                foreach ($data['modifiers'] as $modName) {
                    if (isset($modifiers[$modName])) {
                        $modifierIds[] = $modifiers[$modName]->id;
                    }
                }
                $prod->modifiers()->sync($modifierIds);
            }

            $createdProducts[$data['slug']] = $prod;
        }

        // 5. Seed Historical Orders for Dashboard & Transaction History
        $sampleCustomers = [
            'Dimas Saputra', 'Siti Rahma', 'Budi Santoso', 'Anisa Maharani',
            'Kevin Wijaya', 'Clara Jessica', 'Rizky Pratama', 'Dewi Lestari',
            'Fajar Nugroho', 'Maya Indah', 'Andre Kurniawan', 'Nadia Safitri',
        ];

        $paymentMethods = ['cash', 'qris', 'qris', 'debit', 'ewallet'];
        $orderTypes = ['dine_in', 'take_away'];

        $baseDate = Carbon::today()->setTime(8, 0);

        for ($i = 1; $i <= 18; $i++) {
            $orderTime = (clone $baseDate)->addMinutes($i * 35);
            $orderNumber = 'KS-'.Carbon::today()->format('Ymd').'-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT);
            $customerName = $sampleCustomers[$i % count($sampleCustomers)];
            $payMethod = $paymentMethods[$i % count($paymentMethods)];
            $orderType = $orderTypes[$i % count($orderTypes)];

            // Choose 1-3 random products
            $sampleKeys = array_rand($createdProducts, rand(1, 3));
            if (! is_array($sampleKeys)) {
                $sampleKeys = [$sampleKeys];
            }

            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($sampleKeys as $prodSlug) {
                $product = $createdProducts[$prodSlug];
                $qty = rand(1, 2);
                $unitPrice = $product->price;
                $itemSubtotal = $unitPrice * $qty;
                $temperature = $product->has_temperature ? (rand(0, 1) ? 'Ice' : 'Hot') : null;

                // Optionally attach a modifier
                $itemModifiers = [];
                if ($product->modifiers->isNotEmpty() && rand(0, 1)) {
                    $selectedMod = $product->modifiers->random();
                    $unitPrice += $selectedMod->price;
                    $itemSubtotal = $unitPrice * $qty;
                    $itemModifiers[] = [
                        'modifier_id' => $selectedMod->id,
                        'modifier_name' => $selectedMod->name,
                        'price' => $selectedMod->price,
                    ];
                }

                $subtotal += $itemSubtotal;
                $itemsToCreate[] = [
                    'product' => $product,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'temperature' => $temperature,
                    'modifiers' => $itemModifiers,
                ];
            }

            $discount = ($i % 4 === 0) ? 5000 : 0;
            $taxable = max(0, $subtotal - $discount);
            $tax = round($taxable * 0.10); // 10% PPN
            $serviceCharge = round($taxable * 0.02); // 2% Service
            $total = $taxable + $tax + $serviceCharge;

            $paid = $payMethod === 'cash' ? ceil($total / 50000) * 50000 : $total;
            $change = $paid - $total;

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $cashier->id,
                'customer_name' => $customerName,
                'order_type' => $orderType,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'service_charge' => $serviceCharge,
                'total' => $total,
                'payment_method' => $payMethod,
                'payment_amount' => $paid,
                'change_amount' => $change,
                'status' => 'completed',
                'created_at' => $orderTime,
                'updated_at' => $orderTime,
            ]);

            foreach ($itemsToCreate as $itemData) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'quantity' => $itemData['qty'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['subtotal'],
                    'temperature' => $itemData['temperature'],
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime,
                ]);

                foreach ($itemData['modifiers'] as $modData) {
                    OrderItemModifier::create([
                        'order_item_id' => $orderItem->id,
                        'modifier_id' => $modData['modifier_id'],
                        'modifier_name' => $modData['modifier_name'],
                        'price' => $modData['price'],
                        'created_at' => $orderTime,
                        'updated_at' => $orderTime,
                    ]);
                }
            }
        }
    }
}
