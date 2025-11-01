# Sequence Diagram - Proses Retur (Simplified)

```mermaid
sequenceDiagram
    participant M as 👨‍💼 Manajer;
    participant U as 🖥️ UI;
    participant C as 🌐 Controller;
    participant D as 🗄️ Database;

    Note over M,D: Proses Retur Produk;

    M->>U: 1. Akses Menu Retur;
    activate U
    U->>C: :order(akses_menu_retur);
    activate C
    C->>D: :order(ambil_daftar_retur);
    activate D
    D-->>C: :order(data_retur);
    deactivate D
    C-->>U: :order(daftar_retur);
    deactivate C
    U-->>M: :order(tampilkan_daftar);
    deactivate U

    M->>U: 2. Review Permintaan Retur;
    activate U
    U->>C: :order(lihat_detail_retur, retur_id);
    activate C
    C->>D: :order(ambil_detail_retur);
    activate D
    D-->>C: :order(detail_retur);
    deactivate D
    C-->>U: :order(form_review);
    deactivate C
    U-->>M: :order(tampilkan_detail);
    deactivate U

    M->>U: 3. Keputusan Retur (Setuju/Tolak);
    activate U
    U->>C: :order(proses_keputusan, retur_id, status);
    activate C
    C->>D: :order(update_status_retur, update_stok);
    activate D
    D-->>C: :order(retur_diproses);
    deactivate D
    C-->>U: :order(redirect_retur);
    deactivate C
    U-->>M: :order(tampilkan_konfirmasi);
    deactivate U

    Note right of M: Implementasi Laravel:<br/>ReturnController<br/>ReturnTransaction Model<br/>Product Model<br/>Blade Templates;
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi sederhana antar komponen dalam proses retur produk dengan format 1 actor + 3 objects.

### 👥 **Participants**
- **👨‍💼 Manajer**: Actor yang mengelola proses retur
- **🖥️ UI**: Interface pengguna (Blade templates)
- **🌐 Controller**: ReturnController Laravel
- **🗄️ Database**: MySQL database

### 🔄 **Alur Proses**
1. **Akses Menu Retur**: Manajer melihat daftar permintaan retur
2. **Review Permintaan**: Melihat detail retur yang diajukan
3. **Keputusan Retur**: Menyetujui atau menolak retur

### 💻 **Implementasi Teknis**
- **Laravel Routes**: GET/POST /returns/*
- **Controller**: ReturnController
- **Models**: ReturnTransaction, Product, Transaction
- **Views**: Blade templates untuk UI
- **Database**: MySQL operations untuk update status dan stok
