# Sequence Diagram - Proses Transaksi (Simplified)

```mermaid
sequenceDiagram
    participant P as 👤 Pegawai;
    participant U as 🖥️ UI;
    participant C as 🌐 Controller;
    participant D as 🗄️ Database;

    Note over P,D: Proses Transaksi Penjualan;

    P->>U: 1. Mulai Transaksi;
    activate U
    U->>C: :order(mulai_transaksi);
    activate C
    C->>D: :order(ambil_data_produk);
    activate D
    D-->>C: :order(data_produk);
    deactivate D
    C-->>U: :order(form_transaksi);
    deactivate C
    U-->>P: :order(tampilkan_form);
    deactivate U

    P->>U: 2. Input Data Produk;
    activate U
    U->>C: :order(tambah_item, kode_produk, jumlah);
    activate C
    C->>D: :order(update_stok, insert_item);
    activate D
    D-->>C: :order(item_ditambahkan);
    deactivate D
    C-->>U: :order(keranjang_terupdate);
    deactivate C
    U-->>P: :order(tampilkan_keranjang);
    deactivate U

    P->>U: 3. Proses Pembayaran;
    activate U
    U->>C: :order(proses_pembayaran, total, metode_bayar);
    activate C
    C->>D: :order(simpan_transaksi, update_stok);
    activate D
    D-->>C: :order(transaksi_tersimpan);
    deactivate D
    C-->>U: :order(redirect_invoice);
    deactivate C
    U-->>P: :order(tampilkan_struk);
    deactivate U

    Note right of P: Implementasi Laravel:<br/>TransactionController<br/>Transaction Model<br/>Product Model<br/>Blade Templates;
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi sederhana antar komponen dalam proses transaksi penjualan dengan format 1 actor + 3 objects.

### 👥 **Participants**
- **👤 Pegawai**: Actor yang melakukan transaksi
- **🖥️ UI**: Interface pengguna (Blade templates)
- **🌐 Controller**: TransactionController Laravel
- **🗄️ Database**: MySQL database

### 🔄 **Alur Proses**
1. **Mulai Transaksi**: Pegawai mengakses form transaksi
2. **Input Data Produk**: Menambahkan item ke keranjang
3. **Proses Pembayaran**: Finalisasi transaksi dan cetak struk

### 💻 **Implementasi Teknis**
- **Laravel Routes**: POST /transactions/*
- **Controller**: TransactionController
- **Models**: Transaction, Product, TransactionItem
- **Views**: Blade templates untuk UI
- **Database**: MySQL operations dengan transactions
