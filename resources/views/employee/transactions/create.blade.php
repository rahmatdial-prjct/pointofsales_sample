@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Input Transaksi Penjualan</h1>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-error">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h4>Terjadi Kesalahan!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('employee.transactions.store') }}" method="POST" id="transaction-form">
            @csrf

            <!-- Informasi Pelanggan -->
            <div class="card">
                <div class="card-header">
                    <h2 class="heading-secondary">
                        <i class="fas fa-user"></i>
                        Informasi Pelanggan
                    </h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text"
                               name="customer_name"
                               id="customer_name"
                               class="form-input"
                               value="{{ old('customer_name') }}"
                               placeholder="Masukkan nama pelanggan"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="transaction_date" class="form-label">Tanggal Transaksi (Opsional)</label>
                        <input type="date"
                               name="transaction_date"
                               id="transaction_date"
                               class="form-input"
                               value="{{ old('transaction_date', date('Y-m-d')) }}"
                               placeholder="Pilih tanggal transaksi">
                        <small class="form-help">Kosongkan untuk menggunakan tanggal hari ini</small>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea name="notes"
                                  id="notes"
                                  class="form-input"
                                  rows="3"
                                  placeholder="Catatan tambahan untuk transaksi ini">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card">
                <div class="card-header">
                    <h2 class="heading-secondary">
                        <i class="fas fa-credit-card"></i>
                        Metode Pembayaran
                    </h2>
                </div>
                <div class="card-body">
                    <div class="payment-methods">
                        <div class="payment-option">
                            <input type="radio" name="payment_method" value="cash" id="payment_cash" required checked>
                            <label for="payment_cash" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="payment-text">
                                    <span class="payment-title">Tunai</span>
                                    <span class="payment-desc">Pembayaran cash</span>
                                </div>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" name="payment_method" value="transfer" id="payment_transfer">
                            <label for="payment_transfer" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="payment-text">
                                    <span class="payment-title">Transfer Bank</span>
                                    <span class="payment-desc">Transfer rekening</span>
                                </div>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" name="payment_method" value="qris" id="payment_qris">
                            <label for="payment_qris" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div class="payment-text">
                                    <span class="payment-title">QRIS</span>
                                    <span class="payment-desc">Scan QR Code</span>
                                </div>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" name="payment_method" value="other" id="payment_other">
                            <label for="payment_other" class="payment-label">
                                <div class="payment-icon">
                                    <i class="fas fa-ellipsis-h"></i>
                                </div>
                                <div class="payment-text">
                                    <span class="payment-title">Lainnya</span>
                                    <span class="payment-desc">Metode lain</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="card">
                <div class="card-header">
                    <h2 class="heading-secondary">
                        <i class="fas fa-shopping-cart"></i>
                        Daftar Produk
                    </h2>
                    <button type="button" id="add-item" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </button>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <!-- Items will be added here -->
                    </div>

                    <div class="empty-state" id="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Belum ada produk yang dipilih</p>
                        <small>Klik "Tambah Produk" untuk menambahkan item</small>
                    </div>
                </div>
            </div>

            <!-- Transaction Summary -->
            <div class="card">
                <div class="card-header">
                    <h2 class="heading-secondary">
                        <i class="fas fa-calculator"></i>
                        Ringkasan Transaksi
                    </h2>
                </div>
                <div class="card-body">
                    <div class="summary-grid">
                        <div class="summary-item">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value" id="subtotal">Rp 0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total Diskon:</span>
                            <span class="summary-value discount" id="total-discount">Rp 0</span>
                        </div>
                        <div class="summary-item total">
                            <span class="summary-label">Total Bayar:</span>
                            <span class="summary-value" id="total-amount">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('employee.transactions.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="button" onclick="debugFormState()" class="btn btn-info" style="margin-right: 10px;">
                    <i class="fas fa-bug"></i> Debug Form
                </button>
                <button type="button" onclick="forceAddItem()" class="btn btn-warning" style="margin-right: 10px;">
                    <i class="fas fa-plus"></i> Force Add Item
                </button>
                <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                    <i class="fas fa-save"></i> Simpan Transaksi
                </button>
            </div>
        </form>


    </div>

    <style>
        /* Remove all underlines globally */
        * {
            text-decoration: none !important;
        }

        a, button, .btn, .nav-item, .action-link, .action-card {
            text-decoration: none !important;
        }

        a:hover, button:hover, .btn:hover, .nav-item:hover, .action-link:hover, .action-card:hover {
            text-decoration: none !important;
        }

        /* Container and Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Card Styling */
        .card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 2rem;
        }

        .heading-secondary {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
        }

        /* Dropdown specific styling */
        .form-input.product-dropdown,
        select.form-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-help {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
            display: block;
        }

        /* Discount validation styles */
        .discount-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .discount-warning {
            background: #fef3cd;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: none;
        }

        .discount-warning.show {
            display: block;
        }

        /* Notification Toast */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.5rem;
            z-index: 9999;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 400px;
            min-width: 300px;
        }

        .notification-toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification-toast.notification-warning {
            border-left-color: #f59e0b;
        }

        .notification-toast.notification-error {
            border-left-color: #dc2626;
            background: #fef2f2;
        }

        .notification-toast.notification-success {
            border-left-color: #10b981;
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            color: #374151;
        }

        .notification-content i {
            color: #f59e0b;
            font-size: 1.25rem;
        }

        .notification-warning .notification-content i {
            color: #f59e0b;
        }

        .notification-error .notification-content i {
            color: #dc2626;
        }

        .notification-error .notification-content {
            color: #dc2626;
        }

        .notification-success .notification-content i {
            color: #10b981;
        }

        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .payment-option {
            position: relative;
        }

        .payment-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-label {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            min-height: 80px;
        }

        .payment-label:hover {
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .payment-option input[type="radio"]:checked + .payment-label {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.2);
        }

        .payment-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .payment-text {
            flex: 1;
        }

        .payment-title {
            display: block;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .payment-desc {
            display: block;
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Buttons */
        .btn {
            text-decoration: none !important;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-success:disabled,
        .btn-disabled {
            background: #bdc3c7 !important;
            color: #7f8c8d !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .btn-success:disabled:hover,
        .btn-disabled:hover {
            background: #bdc3c7 !important;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Enhanced visual feedback for enabled state */
        .btn-success:not(:disabled) {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            cursor: pointer;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .btn-outline {
            background: transparent;
            color: #6b7280;
            border: 2px solid #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
            color: #374151;
            border-color: #9ca3af;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        /* Product Items */
        .product-item {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .product-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
        }

        .product-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .product-select,
        .product-quantity,
        .product-discount,
        .product-price,
        .product-subtotal {
            display: flex;
            flex-direction: column;
        }

        .price-display,
        .subtotal-display {
            padding: 0.75rem 1rem;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-weight: 600;
            color: #059669;
            text-align: center;
        }

        /* Summary */
        .summary-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
        }

        .summary-item.total {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 2px solid #3b82f6;
            font-size: 1.125rem;
            font-weight: 700;
        }

        .summary-label {
            font-weight: 600;
            color: #374151;
        }

        .summary-value {
            font-weight: 700;
            color: #059669;
        }

        .summary-value.discount {
            color: #ef4444;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        .empty-state p {
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .empty-state small {
            font-size: 0.875rem;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #f87171;
            color: #991b1b;
        }

        .alert-icon {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .alert-content h4 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .alert-content ul {
            margin: 0;
            padding-left: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }

            .product-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemBtn = document.getElementById('add-item');
            const itemsContainer = document.getElementById('items-container');
            const emptyState = document.getElementById('empty-state');
            const submitBtn = document.getElementById('submit-btn');

            let itemCount = 0;

            // Add item functionality
            addItemBtn.addEventListener('click', function() {
                console.log('Adding new item...');
                addNewItem();
            });

            function addNewItem() {
                const itemIndex = itemCount;
                const itemHTML = `
                    <div class="product-item" data-index="${itemIndex}">
                        <div class="product-row">
                            <div class="product-select">
                                <label class="form-label">Produk</label>
                                <select name="items[${itemIndex}][product_id]" class="form-input product-dropdown" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                                data-price="{{ $product->base_price }}"
                                                data-stock="{{ $product->stock }}"
                                                data-name="{{ $product->name }}"
                                                data-sku="{{ $product->sku }}">
                                            {{ $product->name }} ({{ $product->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="product-quantity">
                                <label class="form-label">Jumlah</label>
                                <input type="number"
                                       name="items[${itemIndex}][quantity]"
                                       min="1"
                                       value="1"
                                       class="form-input quantity-input"
                                       required>
                            </div>

                            <div class="product-discount">
                                <label class="form-label">Diskon (%)</label>
                                <input type="number"
                                       name="items[${itemIndex}][discount]"
                                       min="0"
                                       max="80"
                                       value="0"
                                       class="form-input discount-input"
                                       placeholder="0-80%">
                            </div>

                            <div class="product-price">
                                <label class="form-label">Harga Satuan</label>
                                <div class="price-display">Rp 0</div>
                            </div>

                            <div class="product-subtotal">
                                <label class="form-label">Subtotal</label>
                                <div class="subtotal-display">Rp 0</div>
                            </div>

                            <div class="product-actions">
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                itemsContainer.insertAdjacentHTML('beforeend', itemHTML);
                itemCount++;
                console.log('Item count:', itemCount);

                updateEmptyState();
                attachEventListeners();
                updateSubmitButton();
            }

            // Update empty state visibility
            function updateEmptyState() {
                if (itemCount > 0) {
                    emptyState.style.display = 'none';
                } else {
                    emptyState.style.display = 'block';
                }
            }

            // Update submit button state
            function updateSubmitButton() {
                const submitBtn = document.getElementById('submit-btn');
                if (!submitBtn) {
                    console.log('Submit button not found!');
                    return;
                }

                // Check customer name (minimal 1 karakter, tidak kosong)
                const customerNameInput = document.getElementById('customer_name');
                const hasCustomerName = customerNameInput && customerNameInput.value.trim().length >= 1;

                // Check payment method
                const hasPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

                // Check items
                const productItems = document.querySelectorAll('.product-item');
                const hasItems = productItems.length > 0;

                // Check if all items are valid
                let allItemsValid = true;
                if (productItems.length > 0) {
                    productItems.forEach(item => {
                        const select = item.querySelector('.product-dropdown');
                        const quantity = item.querySelector('.quantity-input');

                        if (!select || !select.value || !quantity || !quantity.value || parseInt(quantity.value) < 1) {
                            allItemsValid = false;
                        }
                    });
                } else {
                    // If no items, set to false
                    allItemsValid = false;
                }

                // Form is valid if all conditions are met
                const isFormValid = hasCustomerName && hasPaymentMethod && hasItems && allItemsValid;

                // Enhanced debug logging
                console.log('=== FORM VALIDATION DEBUG ===');
                console.log('Customer Name Input:', customerNameInput);
                console.log('Customer Name Value:', customerNameInput ? customerNameInput.value : 'NULL');
                console.log('Has Customer Name:', hasCustomerName);
                console.log('Payment Method Selected:', hasPaymentMethod);
                console.log('Product Items Count:', productItems.length);
                console.log('Has Items:', hasItems);
                console.log('All Items Valid:', allItemsValid);
                console.log('Form Valid:', isFormValid);
                console.log('Submit Button:', submitBtn);
                console.log('Submit Button Disabled:', submitBtn.disabled);
                console.log('==============================');

                // Update button state
                submitBtn.disabled = !isFormValid;

                // Visual feedback
                if (isFormValid) {
                    submitBtn.classList.remove('btn-disabled');
                    submitBtn.classList.add('btn-success');
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                } else {
                    submitBtn.classList.add('btn-disabled');
                    submitBtn.classList.remove('btn-success');
                    submitBtn.style.opacity = '0.6';
                    submitBtn.style.cursor = 'not-allowed';
                    submitBtn.style.background = '#bdc3c7';
                }

                // Force visual update
                submitBtn.style.display = 'none';
                submitBtn.offsetHeight; // Trigger reflow
                submitBtn.style.display = '';
            }

            // Attach event listeners to new items
            function attachEventListeners() {
                // Remove item buttons
                document.querySelectorAll('.remove-item').forEach(button => {
                    button.removeEventListener('click', removeItem);
                    button.addEventListener('click', removeItem);
                });

                // Product dropdowns
                document.querySelectorAll('.product-dropdown').forEach(select => {
                    select.removeEventListener('change', handleProductChange);
                    select.addEventListener('change', handleProductChange);
                });

                function handleProductChange(event) {
                    updateProductInfo(event);
                    updateSubmitButton();
                }

                // Quantity inputs
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.removeEventListener('input', handleQuantityChange);
                    input.addEventListener('input', handleQuantityChange);
                });

                // Discount inputs
                document.querySelectorAll('.discount-input').forEach(input => {
                    input.removeEventListener('input', handleDiscountChange);
                    input.addEventListener('input', handleDiscountChange);
                });

                function handleQuantityChange() {
                    calculateSubtotal.call(this);
                    updateSubmitButton();
                }

                function handleDiscountChange() {
                    validateDiscount.call(this);
                    calculateSubtotal.call(this);
                    updateSubmitButton();
                }

                // Payment method radios
                document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                    radio.removeEventListener('change', updateSubmitButton);
                    radio.addEventListener('change', updateSubmitButton);
                });

                // Customer name input - multiple events for better responsiveness
                const customerNameInput = document.getElementById('customer_name');
                if (customerNameInput) {
                    ['input', 'keyup', 'blur', 'change'].forEach(eventType => {
                        customerNameInput.removeEventListener(eventType, updateSubmitButton);
                        customerNameInput.addEventListener(eventType, updateSubmitButton);
                    });
                }
            }

            // Remove item
            function removeItem(event) {
                const productItem = event.target.closest('.product-item');
                productItem.remove();

                // Reindex remaining items
                reindexItems();

                updateEmptyState();
                calculateTotal();
                updateSubmitButton();
            }

            // Reindex items after removal
            function reindexItems() {
                const items = document.querySelectorAll('.product-item');
                itemCount = items.length;

                items.forEach((item, index) => {
                    item.setAttribute('data-index', index);

                    // Update form field names
                    const productSelect = item.querySelector('.product-dropdown');
                    const quantityInput = item.querySelector('.quantity-input');
                    const discountInput = item.querySelector('.discount-input');

                    if (productSelect) productSelect.name = `items[${index}][product_id]`;
                    if (quantityInput) quantityInput.name = `items[${index}][quantity]`;
                    if (discountInput) discountInput.name = `items[${index}][discount]`;
                });
            }

            // Validate discount input (max 80%)
            function validateDiscount() {
                const discountInput = this;
                const productItem = discountInput.closest('.product-item');
                let warningDiv = productItem.querySelector('.discount-warning');

                // Create warning div if it doesn't exist
                if (!warningDiv) {
                    warningDiv = document.createElement('div');
                    warningDiv.className = 'discount-warning';
                    discountInput.parentNode.appendChild(warningDiv);
                }

                const discountValue = parseFloat(discountInput.value) || 0;

                if (discountValue > 80) {
                    // Show error state
                    discountInput.classList.add('discount-error');
                    warningDiv.textContent = '⚠️ Diskon melebihi limit! Maksimal 80%';
                    warningDiv.classList.add('show');

                    // Reset to 80%
                    discountInput.value = 80;

                    // Show notification
                    showNotification('Diskon melebihi limit! Maksimal diskon adalah 80%', 'warning');
                } else if (discountValue < 0) {
                    // Handle negative values
                    discountInput.value = 0;
                    discountInput.classList.remove('discount-error');
                    warningDiv.classList.remove('show');
                } else {
                    // Valid discount
                    discountInput.classList.remove('discount-error');
                    warningDiv.classList.remove('show');
                }
            }

            // Show notification function
            function showNotification(message, type = 'info') {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.notification-toast');
                existingNotifications.forEach(notification => notification.remove());

                // Create notification
                const notification = document.createElement('div');
                notification.className = `notification-toast notification-${type}`;
                notification.innerHTML = `
                    <div class="notification-content">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>${message}</span>
                    </div>
                `;

                // Add to page
                document.body.appendChild(notification);

                // Show notification
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Auto hide after 4 seconds
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 4000);
            }

            // Update product info when product is selected
            function updateProductInfo(event) {
                const select = event.target;
                const option = select.selectedOptions[0];
                const productItem = select.closest('.product-item');

                if (option && option.value) {
                    const price = parseFloat(option.dataset.price);
                    const stock = parseInt(option.dataset.stock);

                    // Update price display
                    const priceDisplay = productItem.querySelector('.price-display');
                    priceDisplay.textContent = 'Rp ' + price.toLocaleString('id-ID');

                    // Update quantity max
                    const quantityInput = productItem.querySelector('.quantity-input');
                    quantityInput.max = stock;

                    calculateSubtotalForItem(productItem);
                }
            }

            // Calculate subtotal for individual item (called from event handlers)
            function calculateSubtotal() {
                const productItem = this.closest('.product-item');
                calculateSubtotalForItem(productItem);
            }

            // Calculate subtotal for specific item (can be called directly)
            function calculateSubtotalForItem(productItem) {
                const select = productItem.querySelector('.product-dropdown');
                const quantityInput = productItem.querySelector('.quantity-input');
                const discountInput = productItem.querySelector('.discount-input');
                const subtotalDisplay = productItem.querySelector('.subtotal-display');

                const option = select.selectedOptions[0];
                if (option && option.value) {
                    const price = parseFloat(option.dataset.price);
                    const quantity = parseInt(quantityInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;

                    const discountAmount = (price * discount) / 100;
                    const finalPrice = price - discountAmount;
                    const subtotal = finalPrice * quantity;

                    subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                }

                calculateTotal();
            }

            // Calculate total for all items
            function calculateTotal() {
                let subtotal = 0;
                let totalDiscount = 0;

                document.querySelectorAll('.product-item').forEach(item => {
                    const select = item.querySelector('.product-dropdown');
                    const quantityInput = item.querySelector('.quantity-input');
                    const discountInput = item.querySelector('.discount-input');

                    const option = select.selectedOptions[0];
                    if (option && option.value) {
                        const price = parseFloat(option.dataset.price);
                        const quantity = parseInt(quantityInput.value) || 0;
                        const discount = parseFloat(discountInput.value) || 0;

                        const itemSubtotal = price * quantity;
                        const discountAmount = (itemSubtotal * discount) / 100;

                        subtotal += itemSubtotal;
                        totalDiscount += discountAmount;
                    }
                });

                const total = subtotal - totalDiscount;

                document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                document.getElementById('total-discount').textContent = 'Rp ' + totalDiscount.toLocaleString('id-ID');
                document.getElementById('total-amount').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                const items = document.querySelectorAll('.product-item');
                console.log('Found items:', items.length);

                if (items.length === 0) {
                    e.preventDefault();
                    alert('Silakan tambahkan minimal satu produk!');
                    return false;
                }

                let hasInvalidItem = false;
                let formData = new FormData();

                items.forEach((item, index) => {
                    const select = item.querySelector('.product-dropdown');
                    const quantity = item.querySelector('.quantity-input');
                    const discount = item.querySelector('.discount-input');

                    console.log(`Item ${index}:`, {
                        product_id: select?.value,
                        quantity: quantity?.value,
                        discount: discount?.value,
                        selectName: select?.name,
                        quantityName: quantity?.name,
                        discountName: discount?.name
                    });

                    // Validate discount doesn't exceed 80%
                    const discountValue = parseFloat(discount?.value) || 0;
                    if (discountValue > 80) {
                        hasInvalidItem = true;
                        console.log(`Item ${index} has invalid discount: ${discountValue}%`);
                        showNotification('Diskon tidak boleh melebihi 80%!', 'error');
                    }

                    if (!select.value || !quantity.value || quantity.value < 1) {
                        hasInvalidItem = true;
                        console.log(`Item ${index} is invalid`);
                    }
                });

                if (hasInvalidItem) {
                    e.preventDefault();
                    alert('Pastikan semua produk telah dipilih dan memiliki jumlah yang valid!');
                    return false;
                }

                console.log('Form validation passed, submitting...');

                // Log all form data for debugging
                const form = e.target;
                const formDataDebug = new FormData(form);
                console.log('Form data being submitted:');
                for (let [key, value] of formDataDebug.entries()) {
                    console.log(`${key}: ${value}`);
                }
            });

            // Initialize
            updateEmptyState();

            // Auto-add first item for better UX
            addNewItem();

            // Ensure first item has proper event listeners and initial validation
            setTimeout(() => {
                attachEventListeners();
                updateSubmitButton();

                // Force initial validation check
                console.log('Initial form state check...');
                const customerName = document.getElementById('customer_name');
                if (customerName) {
                    console.log('Customer name field found, value:', customerName.value);
                }

                // Add real-time validation for customer name
                if (customerName) {
                    customerName.addEventListener('input', function() {
                        console.log('Customer name changed:', this.value, 'Length:', this.value.trim().length);
                        updateSubmitButton();
                    });
                }

                console.log('Initialization complete');
            }, 100);

            // Debug function to help troubleshoot
            window.debugFormState = function() {
                console.log('=== MANUAL DEBUG TRIGGERED ===');

                const customerNameInput = document.getElementById('customer_name');
                const hasCustomerName = customerNameInput && customerNameInput.value.trim().length >= 1;
                const hasPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
                const productItems = document.querySelectorAll('.product-item');
                const hasItems = productItems.length > 0;
                const submitBtn = document.getElementById('submit-btn');

                console.log('Customer Name Input Element:', customerNameInput);
                console.log('Customer Name Value:', customerNameInput ? `"${customerNameInput.value}"` : 'NULL');
                console.log('Customer Name Length:', customerNameInput ? customerNameInput.value.trim().length : 0);
                console.log('Has Customer Name:', hasCustomerName);
                console.log('');
                console.log('Payment Method Selected:', hasPaymentMethod);
                console.log('Payment Method Value:', hasPaymentMethod ? hasPaymentMethod.value : 'NONE');
                console.log('');
                console.log('Product Items Found:', productItems.length);
                console.log('Has Items:', hasItems);
                console.log('');
                console.log('Submit Button Element:', submitBtn);
                console.log('Submit Button Disabled:', submitBtn ? submitBtn.disabled : 'BUTTON NOT FOUND');
                console.log('Submit Button Classes:', submitBtn ? submitBtn.className : 'BUTTON NOT FOUND');

                // Check each product item
                if (productItems.length > 0) {
                    console.log('--- PRODUCT ITEMS DETAILS ---');
                    productItems.forEach((item, index) => {
                        const select = item.querySelector('.product-dropdown');
                        const quantity = item.querySelector('.quantity-input');
                        console.log(`Item ${index + 1}:`);
                        console.log('  Select Element:', select);
                        console.log('  Select Value:', select ? select.value : 'NULL');
                        console.log('  Quantity Element:', quantity);
                        console.log('  Quantity Value:', quantity ? quantity.value : 'NULL');
                    });
                }

                console.log('==============================');

                // Force update submit button
                updateSubmitButton();
            };

            // Force add item function
            window.forceAddItem = function() {
                console.log('Force adding item...');
                addNewItem();
                setTimeout(() => {
                    attachEventListeners();
                    updateSubmitButton();
                }, 100);
            };
        });
    </script>
@endsection
