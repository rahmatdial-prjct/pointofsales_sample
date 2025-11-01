# Sequence Diagram - Return Processing (Technical Implementation Layer)

```mermaid
sequenceDiagram
    participant Manajer as 👤 Manajer
    participant UI as 🖥️ UI
    participant Controller as 🌐 Controller
    participant Database as 🗄️ Database

    %% Manager handles return process
    Manajer->>+UI: 1. Akses halaman retur
    UI->>+Controller: 1.1. Ambil daftar permintaan retur
    Controller->>+Database: 1.2. Query data retur pending
    Database-->>-Controller: 1.3. Data retur yang menunggu
    Controller-->>-UI: 1.4. Data untuk review
    UI-->>-Manajer: 1.5. Tampilkan daftar retur

    Manajer->>+UI: 2. Pilih dan review retur
    UI->>+Controller: 2.1. Ambil detail retur
    Controller->>+Database: 2.2. Query detail retur dan produk
    Database-->>-Controller: 2.3. Data lengkap retur
    Controller-->>-UI: 2.4. Detail untuk review
    UI-->>-Manajer: 2.5. Tampilkan detail retur

    Manajer->>+UI: 3. Setujui/tolak retur
    UI->>+Controller: 3.1. Kirim keputusan
    Controller->>+Database: 3.2. Update status retur
    Database-->>-Controller: 3.3. Status terupdate

    alt Retur disetujui
        Controller->>+Database: 3.4. Kembalikan stok produk
        Database-->>-Controller: 3.5. Stok dikembalikan
        Controller-->>-UI: 3.6. Retur berhasil disetujui
        UI-->>-Manajer: 3.7. Konfirmasi persetujuan
    else Retur ditolak
        Controller-->>-UI: 3.8. Retur ditolak
        UI-->>-Manajer: 3.9. Konfirmasi penolakan
    end

    Note over Manajer,Database: Implementasi Teknis:<br/>- Laravel Controller untuk menangani logic retur<br/>- Database transactions untuk konsistensi data<br/>- Authorization policies untuk kontrol akses<br/>- Status tracking untuk workflow retur
```

## Penjelasan Sequence Diagram - Return Processing (Technical Layer)

### 🎯 **Fokus Teknis**
Diagram ini menggambarkan implementasi teknis Laravel untuk proses retur produk, menunjukkan interaksi antar komponen sistem dengan detail teknis.

### 🏗️ **Komponen Teknis**
- **👤 Manajer**: User dengan role manajer yang menangani proses retur
- **🖥️ UI**: User Interface untuk menampilkan data dan form
- **🌐 Controller**: Laravel Controller yang menangani logic retur
- **🗄️ Database**: Penyimpanan data retur, produk, dan stok

### 🔄 **Alur Implementasi Teknis**

#### **Phase 1: Akses Daftar Retur**

**1. Tampilkan Daftar Retur**
- Manajer mengakses halaman retur
- UI mengirim request ke Controller untuk ambil data
- Controller query Database untuk data retur yang pending
- UI menampilkan daftar retur yang menunggu persetujuan

#### **Phase 2: Review Detail Retur**

**1. Pilih Retur untuk Review**
- Manajer memilih retur dari daftar
- UI mengirim request detail ke Controller
- Controller query Database untuk detail retur dan produk terkait
- UI menampilkan detail lengkap retur untuk review

#### **Phase 3: Keputusan Retur**

**1. Proses Persetujuan/Penolakan**
- Manajer membuat keputusan setujui atau tolak
- UI mengirim keputusan ke Controller
- Controller update status retur di Database

**2. Hasil Keputusan**
- **Jika Disetujui**: Controller kembalikan stok produk ke Database, UI konfirmasi persetujuan
- **Jika Ditolak**: Controller update status saja, UI konfirmasi penolakan

### 🛠️ **Laravel Technical Features**

#### **MVC Architecture**
- **Controllers**: Handle HTTP requests dan business logic
- **Models**: Eloquent ORM untuk database operations
- **Views**: Blade templates untuk UI rendering

#### **Database Management**
- **Transactions**: ACID compliance dengan BEGIN/COMMIT/ROLLBACK
- **Relationships**: Eloquent relationships (`hasMany`, `belongsTo`)
- **Query Builder**: Optimized SQL generation

#### **Security & Authorization**
- **Middleware**: Role-based access control
- **Policies**: Fine-grained authorization rules
- **CSRF Protection**: Built-in security tokens
- **Input Validation**: Server-side validation rules

#### **Error Handling**
- **Exception Handling**: Try-catch blocks
- **Database Rollback**: Automatic pada transaction failures
- **HTTP Status Codes**: Proper response codes
- **Flash Messages**: User feedback system

### 📊 **Database Schema Interactions**

#### **Tables Involved**
- `return_transactions`: Main return records
- `return_items`: Individual returned items
- `products`: Stock updates
- `transactions`: Original transaction reference
- `users`: Employee dan manager information

#### **Key Operations**
- **INSERT**: New return transactions dan items
- **UPDATE**: Status changes dan stock adjustments
- **SELECT**: Data retrieval dengan joins
- **Foreign Key Constraints**: Data integrity

### 🚀 **Performance Considerations**
- **Eager Loading**: Prevent N+1 query problems
- **Database Indexing**: Optimized queries
- **Transaction Batching**: Minimize database round trips
- **Connection Pooling**: Handle concurrent requests

### 🔄 **State Management**
- **Return Status Flow**: `menunggu` → `disetujui`/`ditolak`
- **Stock Tracking**: Real-time inventory updates
- **Audit Trail**: Track approval history
- **Data Consistency**: Transactional integrity
