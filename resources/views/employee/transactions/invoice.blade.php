@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Faktur Digital</h1>
            <p>Faktur untuk transaksi #{{ $transaction->id }}</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">
                    @if(Auth::user()->role === 'director')
                        Direktur
                    @elseif(Auth::user()->role === 'manager')
                        Manajer
                    @elseif(Auth::user()->role === 'employee')
                        Pegawai
                    @else
                        {{ ucfirst(Auth::user()->role) }}
                    @endif
                </div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <div class="invoice-actions">
            <button onclick="printInvoice()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Faktur
            </button>
            <a href="{{ route('employee.transactions.create') }}" class="btn btn-info">
                <i class="fas fa-plus"></i> Transaksi Baru
            </a>
            <a href="{{ route('employee.transactions.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> Daftar Transaksi
            </a>
        </div>

        <div class="invoice-container" id="invoice">
            <!-- Invoice Header -->
            <div class="invoice-header">
                <div class="company-info">
                    <h1>{{ $transaction->branch->name ?? 'Toko Retail' }}</h1>
                    <p>{{ $transaction->branch->address ?? 'Alamat Toko' }}</p>
                    <p>Telp: {{ $transaction->branch->phone ?? '-' }}</p>
                </div>
                <div class="invoice-info">
                    <h2>FAKTUR</h2>
                    <p><strong>No: {{ $transaction->invoice_number }}</strong></p>
                    <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Info Pelanggan -->
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
            <div class="items-section">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Jml</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="product-detail">
                                        <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                        <br><small>SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->discount_percentage > 0)
                                        {{ $item->discount_percentage }}%
                                        <br><small>(Rp {{ number_format($item->discount_amount, 0, ',', '.') }})</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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
                        <span><strong>TOTAL BAYAR:</strong></span>
                        <span><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                <div class="footer-note">
                    <p>Terima kasih atas kunjungan Anda!</p>
                    <p>Barang yang sudah dibeli tidak dapat dikembalikan kecuali ada kesepakatan.</p>
                </div>
                <div class="footer-signature">
                    <p>Hormat kami,</p>
                    <br><br>
                    <p>{{ $transaction->employee->name ?? 'Kasir' }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .invoice-actions {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .invoice-actions .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
        }

        .invoice-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .company-info h1 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
            font-size: 1.5rem;
        }

        .company-info p {
            margin: 0.25rem 0;
            color: #6b7280;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
            font-size: 2rem;
        }

        .invoice-info p {
            margin: 0.25rem 0;
            color: #374151;
        }

        .customer-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
        }

        .customer-info h3 {
            margin: 0 0 0.5rem 0;
            color: #374151;
            font-size: 1rem;
        }

        .customer-info p {
            margin: 0.25rem 0;
        }

        .transaction-info p {
            margin: 0.25rem 0;
            color: #6b7280;
        }

        .items-section {
            margin-bottom: 2rem;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .invoice-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .product-detail strong {
            color: #1f2937;
        }

        .product-detail small {
            color: #6b7280;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
        }

        .totals-table {
            min-width: 300px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .final-total {
            border-bottom: 2px solid #374151;
            border-top: 2px solid #374151;
            font-size: 1.125rem;
            color: #1f2937;
        }

        .invoice-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .footer-note p {
            margin: 0.25rem 0;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .footer-signature {
            text-align: center;
        }

        .footer-signature p {
            margin: 0.25rem 0;
            color: #374151;
        }

        /* Print Styles */
        @media print {
            /* Hide all non-invoice elements */
            .top-bar,
            .invoice-actions,
            nav,
            .navbar,
            .sidebar,
            .footer,
            .btn,
            button {
                display: none !important;
            }

            /* Reset page layout for print */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                font-size: 12pt !important;
                line-height: 1.4 !important;
            }

            .container {
                margin: 0 !important;
                padding: 0 !important;
                max-width: none !important;
                width: 100% !important;
            }

            .invoice-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 20px !important;
                margin: 0 !important;
                max-width: none !important;
                width: 100% !important;
                background: white !important;
            }

            /* Ensure proper page breaks */
            .invoice-header {
                page-break-inside: avoid;
            }

            .invoice-table {
                page-break-inside: auto;
            }

            .invoice-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .totals-section,
            .invoice-footer {
                page-break-inside: avoid;
            }

            /* Adjust font sizes for print */
            .company-info h1 {
                font-size: 18pt !important;
            }

            .invoice-info h2 {
                font-size: 24pt !important;
            }

            .invoice-table th,
            .invoice-table td {
                font-size: 10pt !important;
                padding: 8px 4px !important;
            }

            .final-total {
                font-size: 12pt !important;
                font-weight: bold !important;
            }
        }

        @media (max-width: 768px) {
            .invoice-actions {
                justify-content: center;
                flex-direction: column;
            }

            .invoice-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .invoice-header,
            .customer-section,
            .invoice-footer {
                flex-direction: column;
                gap: 1rem;
            }

            .invoice-info {
                text-align: left;
            }

            .totals-section {
                justify-content: stretch;
            }

            .totals-table {
                min-width: 100%;
            }

            .invoice-table {
                font-size: 0.8rem;
            }

            .invoice-table th,
            .invoice-table td {
                padding: 0.5rem 0.25rem;
            }
        }
    </style>

    <script>
        function printInvoice() {
            // Hide all non-invoice elements before printing
            const elementsToHide = document.querySelectorAll('.top-bar, .invoice-actions, nav, .navbar, .sidebar, .footer');
            elementsToHide.forEach(el => {
                if (el) el.style.display = 'none';
            });

            // Focus on invoice container
            const invoiceContainer = document.getElementById('invoice');
            if (invoiceContainer) {
                invoiceContainer.focus();
            }

            // Print the page
            window.print();

            // Restore hidden elements after printing
            setTimeout(() => {
                elementsToHide.forEach(el => {
                    if (el) el.style.display = '';
                });
            }, 1000);
        }



        // Auto-focus for better print experience
        window.addEventListener('beforeprint', function() {
            document.title = 'Faktur {{ $transaction->invoice_number }}';
        });

        window.addEventListener('afterprint', function() {
            document.title = 'Faktur Digital';
        });
    </script>
@endsection
