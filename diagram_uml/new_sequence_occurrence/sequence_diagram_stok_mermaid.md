# Sequence Diagram - Manajemen Stok (Simplified)

```mermaid
sequenceDiagram
    participant M as 👨‍💼 Manajer;
    participant U as 🖥️ UI;
    participant C as 🌐 Controller;
    participant D as 🗄️ Database;

    Note over M,D: Manajemen Stok Produk;

    M->>U: 1. Akses Menu Produk;
    activate U
    U->>C: :order(akses_menu_produk);
    activate C
    C->>D: :order(ambil_data_produk_stok);
    activate D
    D-->>C: :order(data_produk);
    deactivate D
    C-->>U: :order(daftar_produk);
    deactivate C
    U-->>M: :order(tampilkan_daftar);
    deactivate U

    M->>U: 2. Kelola Produk (Create/Edit);
    activate U
    U->>C: :order(kelola_produk, nama, kategori, harga);
    activate C
    C->>D: :order(simpan_produk);
    activate D
    D-->>C: :order(produk_tersimpan);
    deactivate D
    C-->>U: :order(redirect_produk);
    deactivate C
    U-->>M: :order(tampilkan_konfirmasi);
    deactivate U

    M->>U: 3. Update Stok;
    activate U
    U->>C: :order(update_stok, produk_id, jumlah_stok);
    activate C
    C->>D: :order(update_stok, insert_history);
    activate D
    D-->>C: :order(stok_terupdate);
    deactivate D
    C-->>U: :order(info_stok_terbaru);
    deactivate C
    U-->>M: :order(tampilkan_stok);
    deactivate U

    Note right of M: Implementasi Laravel:<br/>ProductController<br/>Product Model<br/>Category Model<br/>Blade Templates;
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi sederhana antar komponen dalam manajemen stok produk dengan format 1 actor + 3 objects.

### 👥 **Participants**
- **👨‍💼 Manajer**: Actor yang mengelola produk dan stok
- **🖥️ UI**: Interface pengguna (Blade templates)
- **🌐 Controller**: ProductController Laravel
- **🗄️ Database**: MySQL database

### 🔄 **Alur Proses**
1. **Akses Menu Produk**: Manajer melihat daftar produk dan stok
2. **Kelola Produk**: Menambah atau mengedit produk
3. **Update Stok**: Mengubah jumlah stok produk

### 💻 **Implementasi Teknis**
- **Laravel Routes**: GET/POST /products/*
- **Controller**: ProductController
- **Models**: Product, Category, StockHistory
- **Views**: Blade templates untuk UI
- **Database**: MySQL operations untuk CRUD produk dan stok
