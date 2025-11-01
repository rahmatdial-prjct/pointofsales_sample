@extends('layouts.app')

@section('content')
<style>
    .return-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-subtitle {
        margin: 10px 0 0 0;
        opacity: 1;
        font-size: 16px;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .form-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e1e8ed;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #34495e;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    /* Dropdown specific styling */
    .form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .transaction-selector {
        background: #f8f9fa;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .search-container {
        margin-bottom: 15px;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 45px;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .search-input::placeholder {
        color: #a0a0a0;
        font-style: italic;
    }

    .search-clear {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #7f8c8d;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s ease;
        display: none;
    }

    .search-clear:hover {
        background: #f1f3f4;
        color: #e74c3c;
    }

    .search-clear.visible {
        display: block;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #7f8c8d;
        font-size: 16px;
    }

    .transaction-list {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #e1e8ed;
        border-radius: 8px;
        background: white;
    }

    .transaction-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border-bottom: 1px solid #f1f3f4;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .transaction-option:last-child {
        border-bottom: none;
    }

    .transaction-option:hover {
        background: #f8f9ff;
    }

    .transaction-option.selected {
        background: #f0f4ff;
        border-left: 4px solid #667eea;
    }

    .transaction-option.hidden {
        display: none;
    }

    .transaction-info {
        flex: 1;
    }

    .transaction-number {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .transaction-details {
        font-size: 12px;
        color: #7f8c8d;
        line-height: 1.4;
    }

    .no-results {
        padding: 20px;
        text-align: center;
        color: #7f8c8d;
        font-style: italic;
        display: none;
    }

    .transaction-count {
        font-size: 12px;
        color: #7f8c8d;
        margin-bottom: 10px;
        text-align: right;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .search-tips {
        font-size: 11px;
        color: #a0a0a0;
        font-style: italic;
    }

    .highlight {
        background: #fff3cd;
        padding: 1px 3px;
        border-radius: 3px;
        font-weight: 600;
    }

    .items-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .item-row {
        background: white;
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
    }

    .item-row:last-child {
        margin-bottom: 0;
    }

    .item-grid {
        display: grid;
        grid-template-columns: 3fr 1fr 2fr auto;
        gap: 15px;
        align-items: end;
    }

    .remove-item-btn {
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .remove-item-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .add-item-btn {
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .add-item-btn:hover {
        background: #229954;
        transform: translateY(-2px);
    }

    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 15px 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 auto;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .error-alert {
        background: #fee;
        border: 2px solid #fcc;
        color: #c33;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .error-list {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }

    .condition-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .condition-option {
        position: relative;
    }

    .condition-radio {
        position: absolute;
        opacity: 0;
    }

    .condition-label {
        display: block;
        padding: 12px 15px;
        border: 2px solid #e1e8ed;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        font-weight: 600;
        background: white;
        user-select: none;
    }

    .condition-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .condition-label:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .condition-radio:checked + .condition-label {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        font-weight: 700;
    }

    /* Default state colors */
    .condition-label.condition-good {
        border-color: #e1e8ed;
        color: #7f8c8d;
    }

    .condition-label.condition-damaged {
        border-color: #e1e8ed;
        color: #7f8c8d;
    }

    .condition-label.condition-defective {
        border-color: #e1e8ed;
        color: #7f8c8d;
    }

    /* Selected state colors */
    .condition-radio:checked + .condition-label.condition-good {
        border-color: #27ae60 !important;
        background: #eafaf1 !important;
        color: #27ae60 !important;
    }

    .condition-radio:checked + .condition-label.condition-damaged {
        border-color: #f39c12 !important;
        background: #fef9e7 !important;
        color: #f39c12 !important;
    }

    .condition-radio:checked + .condition-label.condition-defective {
        border-color: #e74c3c !important;
        background: #fdedec !important;
        color: #e74c3c !important;
    }

    .product-option {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f3f4;
    }

    .product-option:last-child {
        border-bottom: none;
    }

    .product-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-name {
        font-weight: 600;
        color: #2c3e50;
    }

    .product-details {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 2px;
    }

    .stock-badge {
        background: #e8f5e8;
        color: #27ae60;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .stock-badge.low {
        background: #fef9e7;
        color: #f39c12;
    }

    .stock-badge.out {
        background: #fdedec;
        color: #e74c3c;
    }

    @media (max-width: 768px) {
        .item-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .condition-selector {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="return-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-undo-alt"></i>
            Pengajuan Retur Barang
        </h1>
        <p class="page-subtitle">Ajukan retur untuk barang yang rusak atau tidak sesuai</p>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="error-alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Return Form -->
    <div class="form-card">
        <form action="{{ route('employee.returns.store') }}" method="POST" id="return-form">
            @csrf

            <!-- Transaction Selection -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-receipt"></i>
                    Referensi Transaksi (Opsional)
                </h3>

                <div class="transaction-selector">
                    <!-- Default Option -->
                    <div class="transaction-option" data-transaction-id="">
                        <input type="radio" name="transaction_id" value="" id="no-transaction" checked>
                        <label for="no-transaction" class="transaction-info">
                            <div class="transaction-number">
                                <i class="fas fa-file-alt"></i> Retur Umum
                            </div>
                            <div class="transaction-details">Retur tanpa referensi transaksi spesifik</div>
                        </label>
                    </div>

                    @if($recentTransactions->count() > 0)
                        <!-- Search Box -->
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   id="transaction-search"
                                   class="search-input"
                                   placeholder="Cari berdasarkan nomor invoice, nama customer, atau tanggal..."
                                   autocomplete="off">
                            <button type="button" class="search-clear" id="search-clear">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Transaction Count -->
                        <div class="transaction-count">
                            <div class="search-tips">💡 Tips: Gunakan Ctrl+F untuk fokus pencarian</div>
                            <div>
                                <span id="visible-count">{{ $recentTransactions->count() }}</span> dari {{ $recentTransactions->count() }} transaksi
                            </div>
                        </div>

                        <!-- Transaction List -->
                        <div class="transaction-list" id="transaction-list">
                            @foreach($recentTransactions as $transaction)
                                <div class="transaction-option"
                                     data-transaction-id="{{ $transaction->id }}"
                                     data-search-text="{{ strtolower($transaction->invoice_number . ' ' . $transaction->customer_name . ' ' . $transaction->created_at->format('d/m/Y')) }}">
                                    <input type="radio"
                                           name="transaction_id"
                                           value="{{ $transaction->id }}"
                                           id="transaction-{{ $transaction->id }}">
                                    <label for="transaction-{{ $transaction->id }}" class="transaction-info">
                                        <div class="transaction-number">
                                            <i class="fas fa-receipt"></i>
                                            {{ $transaction->invoice_number }}
                                        </div>
                                        <div class="transaction-details">
                                            <i class="fas fa-calendar"></i> {{ $transaction->created_at->format('d/m/Y H:i') }} <br>
                                            <i class="fas fa-user"></i> {{ $transaction->customer_name }} <br>
                                            <i class="fas fa-money-bill-wave"></i> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </div>
                                    </label>
                                </div>
                            @endforeach

                            <!-- No Results Message -->
                            <div class="no-results" id="no-results">
                                <i class="fas fa-search"></i><br>
                                Tidak ada transaksi yang sesuai dengan pencarian
                            </div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 20px; color: #7f8c8d;">
                            <i class="fas fa-info-circle"></i>
                            Tidak ada transaksi terbaru untuk dijadikan referensi
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reason -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-comment-alt"></i>
                    Alasan Retur
                </h3>

                <div class="form-group">
                    <label for="reason" class="form-label">Jelaskan alasan retur</label>
                    <textarea name="reason" id="reason" class="form-textarea" placeholder="Contoh: Barang rusak saat diterima, tidak sesuai pesanan, dll." required>{{ old('reason') }}</textarea>
                </div>
            </div>

            <!-- Items Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-boxes"></i>
                    Daftar Barang yang Diretur
                </h3>

                <div class="items-container" id="items-container">
                    <!-- Items will be added here -->
                </div>

                <button type="button" id="add-item" class="add-item-btn">
                    <i class="fas fa-plus"></i>
                    Tambah Barang
                </button>
            </div>

            <!-- Submit Button -->
            <div class="form-section" style="text-align: center;">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Pengajuan Retur
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let itemIndex = 0;

    const addItemBtn = document.getElementById('add-item');
    const itemsContainer = document.getElementById('items-container');
    const transactionOptions = document.querySelectorAll('input[name="transaction_id"]');

    // Products data for JavaScript
    const products = @json($products);

    // Add first item automatically
    addItem();

    // Transaction selection handlers
    transactionOptions.forEach(option => {
        option.addEventListener('change', function() {
            document.querySelectorAll('.transaction-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            this.closest('.transaction-option').classList.add('selected');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('transaction-search');
    const searchClear = document.getElementById('search-clear');
    const transactionList = document.getElementById('transaction-list');
    const noResults = document.getElementById('no-results');
    const visibleCount = document.getElementById('visible-count');

    if (searchInput) {
        // Search input handler
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const transactionOptions = transactionList.querySelectorAll('.transaction-option[data-search-text]');
            let visibleTransactions = 0;

            // Show/hide clear button
            if (searchClear) {
                if (searchTerm) {
                    searchClear.classList.add('visible');
                } else {
                    searchClear.classList.remove('visible');
                }
            }

            // Filter transactions
            transactionOptions.forEach(option => {
                const searchText = option.getAttribute('data-search-text');

                if (searchText.includes(searchTerm)) {
                    option.classList.remove('hidden');
                    visibleTransactions++;

                    // Highlight search term
                    if (searchTerm) {
                        highlightSearchTerm(option, searchTerm);
                    } else {
                        removeHighlight(option);
                    }
                } else {
                    option.classList.add('hidden');
                    removeHighlight(option);
                }
            });

            // Update visible count
            if (visibleCount) {
                visibleCount.textContent = visibleTransactions;
            }

            // Show/hide no results message
            if (noResults) {
                if (visibleTransactions === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });

        // Clear search button
        if (searchClear) {
            searchClear.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });
        }

        // Clear search on escape key
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('input'));
                this.blur();
            }
        });

        // Focus search with Ctrl+F
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });
    }

    // Add item button
    addItemBtn.addEventListener('click', addItem);

    function addItem() {
        // Generate product options with stock information
        let productOptions = '<option value="">Pilih Produk</option>';
        products.forEach(product => {
            const stockStatus = product.stock <= 0 ? ' (Habis)' :
                               product.stock <= 10 ? ` (Stock: ${product.stock} - Rendah)` :
                               ` (Stock: ${product.stock})`;
            productOptions += `<option value="${product.id}" ${product.stock <= 0 ? 'disabled' : ''}>${product.name} (${product.sku})${stockStatus}</option>`;
        });

        const itemHtml = `
            <div class="item-row" data-index="${itemIndex}">
                <div class="item-grid">
                    <div class="form-group">
                        <label class="form-label">Produk</label>
                        <select name="items[${itemIndex}][product_id]" class="form-select" required>
                            ${productOptions}
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kondisi</label>
                        <div class="condition-selector">
                            <div class="condition-option">
                                <input type="radio" name="items[${itemIndex}][condition]" value="good" id="condition-good-${itemIndex}" class="condition-radio" required>
                                <label for="condition-good-${itemIndex}" class="condition-label condition-good">Baik</label>
                            </div>
                            <div class="condition-option">
                                <input type="radio" name="items[${itemIndex}][condition]" value="damaged" id="condition-damaged-${itemIndex}" class="condition-radio">
                                <label for="condition-damaged-${itemIndex}" class="condition-label condition-damaged">Rusak</label>
                            </div>
                            <div class="condition-option">
                                <input type="radio" name="items[${itemIndex}][condition]" value="defective" id="condition-defective-${itemIndex}" class="condition-radio">
                                <label for="condition-defective-${itemIndex}" class="condition-label condition-defective">Cacat</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="align-self: end;">
                        <button type="button" class="remove-item-btn" onclick="removeItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        itemsContainer.insertAdjacentHTML('beforeend', itemHtml);

        // Add event listener for product selection
        const newSelect = itemsContainer.querySelector(`select[name="items[${itemIndex}][product_id]"]`);
        const newQuantityInput = itemsContainer.querySelector(`input[name="items[${itemIndex}][quantity]"]`);

        if (newSelect && newQuantityInput) {
            newSelect.addEventListener('change', function() {
                const selectedProduct = products.find(p => p.id == this.value);
                if (selectedProduct) {
                    newQuantityInput.max = selectedProduct.stock;
                    newQuantityInput.title = `Stock tersedia: ${selectedProduct.stock}`;

                    // Update quantity if current value exceeds stock
                    if (parseInt(newQuantityInput.value) > selectedProduct.stock) {
                        newQuantityInput.value = Math.min(selectedProduct.stock, 1);
                    }
                }
            });

            newQuantityInput.addEventListener('input', function() {
                const selectedProduct = products.find(p => p.id == newSelect.value);
                if (selectedProduct && parseInt(this.value) > selectedProduct.stock) {
                    this.value = selectedProduct.stock;
                    alert(`Jumlah tidak boleh melebihi stock yang tersedia (${selectedProduct.stock})`);
                }
            });
        }

        itemIndex++;
    }

    // Highlight search terms
    function highlightSearchTerm(element, searchTerm) {
        const textElements = element.querySelectorAll('.transaction-number, .transaction-details');

        textElements.forEach(textEl => {
            const originalText = textEl.getAttribute('data-original-text') || textEl.textContent;
            if (!textEl.getAttribute('data-original-text')) {
                textEl.setAttribute('data-original-text', originalText);
            }

            const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
            const highlightedText = originalText.replace(regex, '<span class="highlight">$1</span>');
            textEl.innerHTML = highlightedText;
        });
    }

    function removeHighlight(element) {
        const textElements = element.querySelectorAll('.transaction-number, .transaction-details');

        textElements.forEach(textEl => {
            const originalText = textEl.getAttribute('data-original-text');
            if (originalText) {
                textEl.textContent = originalText;
            }
        });
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    window.removeItem = function(button) {
        const itemRow = button.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            itemRow.remove();
        } else {
            alert('Minimal harus ada satu barang untuk diretur');
        }
    };

    // Form validation
    document.getElementById('return-form').addEventListener('submit', function(e) {
        const items = document.querySelectorAll('.item-row');
        let isValid = true;

        items.forEach(item => {
            const productSelect = item.querySelector('select[name*="[product_id]"]');
            const quantityInput = item.querySelector('input[name*="[quantity]"]');
            const conditionRadios = item.querySelectorAll('input[name*="[condition]"]');

            if (!productSelect.value) {
                isValid = false;
                productSelect.style.borderColor = '#e74c3c';
            } else {
                productSelect.style.borderColor = '#e1e8ed';
            }

            if (!quantityInput.value || quantityInput.value < 1) {
                isValid = false;
                quantityInput.style.borderColor = '#e74c3c';
            } else {
                quantityInput.style.borderColor = '#e1e8ed';
            }

            const conditionSelected = Array.from(conditionRadios).some(radio => radio.checked);
            if (!conditionSelected) {
                isValid = false;
                conditionRadios.forEach(radio => {
                    radio.nextElementSibling.style.borderColor = '#e74c3c';
                });
            } else {
                conditionRadios.forEach(radio => {
                    radio.nextElementSibling.style.borderColor = '#e1e8ed';
                });
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang diperlukan');
        }
    });
});
</script>
@endsection
