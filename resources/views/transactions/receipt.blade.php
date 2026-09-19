<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ $order->order_number }} — Kopi Senja</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            color: #000;
        }
        .receipt-container {
            width: 78mm;
            background: #fff;
            padding: 16px 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            font-size: 11px;
            line-height: 1.35;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .double-divider {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .btn-print {
            background: #382012;
            color: #fff;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 12px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                width: 100%;
                padding: 4px;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div style="display: flex; flex-direction: column; align-items: center;">
        <div class="no-print" style="margin-bottom: 12px; display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak Struk (Print)</button>
            <button onclick="window.close()" class="btn-print" style="background: #6b7280;">Tutup</button>
        </div>

        <div class="receipt-container" id="thermal-receipt">
            <!-- Header -->
            <div class="text-center">
                <div class="font-bold" style="font-size: 16px; letter-spacing: 1px;">KOPI SENJA</div>
                <div style="font-size: 10px; font-weight: bold;">COFFEE & MORE</div>
                <div style="font-size: 9px; margin-top: 3px;">Jl. Senja Raya No. 24, Jakarta Selatan</div>
                <div style="font-size: 9px;">Telp: 0812-8899-2345 • IG: @kopisenja.id</div>
            </div>

            <div class="double-divider"></div>

            <!-- Meta info -->
            <div>
                <div class="flex justify-between">
                    <span>No. Trans:</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Waktu    :</span>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir    :</span>
                    <span>{{ $order->user->name ?? 'Kasir' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Customer :</span>
                    <span>{{ $order->customer_name }} ({{ strtoupper($order->order_type) }})</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Items -->
            <div>
                @foreach($order->items as $item)
                    <div style="margin-bottom: 4px;">
                        <div class="font-bold">{{ $item->product_name }}</div>
                        <div class="flex justify-between" style="font-size: 10px;">
                            <span>
                                {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                @if($item->temperature)
                                    [{{ $item->temperature }}]
                                @endif
                            </span>
                            <span class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($item->modifiers->isNotEmpty())
                            @foreach($item->modifiers as $mod)
                                <div style="font-size: 9px; padding-left: 8px; color: #333;">
                                    + {{ $mod->modifier_name }} (Rp {{ number_format($mod->price, 0, ',', '.') }})
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="divider"></div>

            <!-- Totals -->
            <div>
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>PPN (10%)</span>
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Service (2%)</span>
                    <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                </div>

                <div class="double-divider"></div>

                <div class="flex justify-between font-bold" style="font-size: 13px;">
                    <span>TOTAL</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>

                <div class="divider"></div>

                <div class="flex justify-between">
                    <span>Pembayaran:</span>
                    <span class="font-bold">{{ strtoupper($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Diterima  :</span>
                    <span>Rp {{ number_format($order->payment_amount, 0, ',', '.') }}</span>
                </div>
                @if($order->payment_method === 'cash')
                    <div class="flex justify-between font-bold">
                        <span>Kembalian :</span>
                        <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <div class="double-divider"></div>

            <!-- Footer note -->
            <div class="text-center" style="font-size: 9px; margin-top: 6px;">
                <div class="font-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA!</div>
                <div>Wifi: KopiSenjaGuest • Pass: kopisenja2026</div>
                <div style="margin-top: 4px;">Layanan Konsumen: halo@kopisenja.com</div>
            </div>
        </div>
    </div>

</body>
</html>
