<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .invoice-header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .company-info {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .company-info h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .company-info p {
            margin-bottom: 5px;
            color: #666;
        }

        .invoice-info {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .invoice-info h2 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .invoice-info p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .customer-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .customer-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .transaction-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }

        .customer-info h3,
        .transaction-info h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .customer-info p,
        .transaction-info p {
            margin-bottom: 5px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            font-size: 11px;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-detail strong {
            font-weight: bold;
        }

        .product-detail small {
            color: #666;
            font-size: 10px;
        }

        .totals-section {
            margin-top: 30px;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .total-row span:first-child {
            display: table-cell;
            text-align: left;
            padding-right: 20px;
        }

        .total-row span:last-child {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .final-total {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
        }

        .final-total span {
            font-weight: bold;
        }

        .footer-section {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .footer-section p {
            margin-bottom: 5px;
            color: #666;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>{{ $transaction->branch->name ?? 'Toko Retail' }}</h1>
                <p>{{ $transaction->branch->address ?? 'Alamat Toko' }}</p>
                <p>Telp: {{ $transaction->branch->phone ?? '-' }}</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>No: {{ $transaction->invoice_number }}</strong></p>
                <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-section">
            <div class="customer-info">
                <h3>Kepada:</h3>
                <p><strong>{{ $transaction->customer_name }}</strong></p>
            </div>
            <div class="transaction-info">
                <p><strong>Kasir:</strong> {{ $transaction->employee->name ?? 'N/A' }}</p>
                <p><strong>Metode Bayar:</strong> {{ strtoupper($transaction->payment_method) }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Produk</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Harga</th>
                    <th style="width: 15%;">Diskon</th>
                    <th style="width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="product-detail">
                                <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                <br><small>SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                            </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->discount_percentage > 0)
                                {{ $item->discount_percentage }}%
                                <br><small>(Rp {{ number_format($item->discount_amount, 0, ',', '.') }})</small>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-table">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaction->discount_amount > 0)
                    <div class="total-row">
                        <span>Total Diskon:</span>
                        <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="total-row final-total">
                    <span>TOTAL BAYAR:</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Invoice ini dicetak pada {{ now()->format('d/m/Y H:i:s') }}</p>
            @if($transaction->notes)
                <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
            @endif
        </div>
    </div>
</body>
</html>
