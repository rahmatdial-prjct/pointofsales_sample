# Sequence Diagram - Transaction Processing (Technical Implementation Layer)

```mermaid
sequenceDiagram
    participant Pegawai as 👤 Pegawai
    participant UI as 🖥️ UI
    participant Controller as 🌐 Controller
    participant Database as 🗄️ Database

    %% Transaction Creation Process
    Pegawai->>+UI: 1. Input data transaksi
    UI->>+Controller: 1.1. Kirim data penjualan
    Controller->>+Database: 1.2. Validasi produk dan stok
    Database-->>-Controller: 1.3. Data produk valid

    alt Stok mencukupi
        Controller->>+Database: 1.4. Simpan transaksi
        Database-->>-Controller: 1.5. Transaksi tersimpan
        Controller->>+Database: 1.6. Update stok produk
        Database-->>-Controller: 1.7. Stok terupdate
        Controller-->>-UI: 1.8. Redirect ke invoice
        UI-->>-Pegawai: 1.9. Tampilkan struk penjualan
    else Stok tidak mencukupi
        Controller-->>-UI: 1.10. Error stok habis
        UI-->>-Pegawai: 1.11. Tampilkan pesan error
    end

    %% Invoice Generation
    Pegawai->>+UI: 2. Cetak ulang struk
    UI->>+Controller: 2.1. Request data invoice
    Controller->>+Database: 2.2. Ambil data transaksi
    Database-->>-Controller: 2.3. Data transaksi lengkap
    Controller-->>-UI: 2.4. Data untuk invoice
    UI-->>-Pegawai: 2.5. Tampilkan/cetak struk

    Note over Pegawai,Database: Implementasi Teknis:<br/>- Laravel Controller untuk HTTP requests<br/>- Database transactions untuk konsistensi data<br/>- Eloquent Models untuk operasi database<br/>- Blade views untuk rendering halaman
```

## Penjelasan Sequence Diagram - Transaction Processing (Technical Layer)

### 🎯 **Fokus Teknis**
Diagram ini menggambarkan implementasi teknis Laravel untuk proses transaksi penjualan, menunjukkan interaksi antar komponen sistem secara detail.

### 🏗️ **Komponen Teknis**
- **👤 Pegawai**: User dengan role pegawai yang melakukan transaksi
- **🖥️ UI**: User Interface untuk input dan tampilan data
- **🌐 Controller**: Laravel Controller yang menangani logic transaksi
- **🗄️ Database**: Penyimpanan data transaksi, produk, dan stok

### 🔄 **Alur Implementasi Teknis**

#### **1. HTTP Request Processing**
- Pegawai mengirim POST request ke `/employee/transactions/store`
- Controller menerima data: `customer_name`, `items[]`, `payment_method`
- Validasi input menggunakan Laravel validation rules
- Memulai database transaction untuk data consistency

#### **2. Product Validation Loop**
- Untuk setiap item dalam array `items[]`:
  - Query database untuk mendapatkan data produk
  - Validasi ketersediaan stok
  - Hitung subtotal dengan discount
  - Rollback jika stok tidak mencukupi

#### **3. Transaction Creation**
- Instantiate Transaction model baru
- Set attributes: `branch_id`, `user_id`, `customer_name`, dll
- Generate unique invoice number
- Save transaction ke database dengan INSERT query

#### **4. Transaction Items Processing**
- Loop untuk setiap validated item:
  - Create TransactionItem model instance
  - Set attributes: `transaction_id`, `product_id`, `quantity`, dll
  - Save item ke database
  - Update stock produk dengan decrement operation

#### **5. Total Calculation & Commit**
- Calculate total amount dari semua items
- Update transaction dengan total amount
- Commit database transaction
- Redirect ke invoice page

#### **6. Invoice Generation**
- GET request ke invoice route
- Load transaction dengan eager loading (`with('items.product')`)
- Render Blade view dengan transaction data
- Display invoice page ke pegawai

### 🛠️ **Laravel Technical Features**
- **Eloquent ORM**: Model relationships dan query builder
- **Database Transactions**: Ensure ACID properties
- **Request Validation**: Built-in validation rules
- **Route Model Binding**: Automatic model resolution
- **Blade Templates**: Server-side rendering
- **Middleware**: Authentication dan authorization

### 🔒 **Error Handling**
- Stock validation dengan rollback mechanism
- Database constraint violations
- HTTP status codes untuk different scenarios
- Exception handling dengan try-catch blocks

### 📊 **Database Operations**
- **INSERT**: Transactions dan transaction_items tables
- **UPDATE**: Products stock decrement
- **SELECT**: Product data retrieval dengan joins
- **Transaction Management**: BEGIN, COMMIT, ROLLBACK

### 🚀 **Performance Considerations**
- Eager loading untuk menghindari N+1 queries
- Database indexing pada foreign keys
- Batch operations untuk multiple items
- Connection pooling untuk concurrent requests
