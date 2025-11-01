# Activity Diagram - Proses Transaksi Penjualan (Simplified)

```mermaid
flowchart TD
    subgraph Pegawai ["👤 Pegawai"]
        Start([🟢 Mulai])
        MulaiTrans[Mulai Transaksi]
        ScanProd[Scan Produk]
        InputJumlah[Input Jumlah]
        KonfirmasiBayar[Konfirmasi Pembayaran]
        CetakStruk[Cetak Struk]
        End([🔴 Selesai])
    end

    subgraph SistemPOS ["💻 Sistem POS"]
        ValidasiProduk[Validasi Produk & Stok]
        HitungTotal[Hitung Total]
        ProsesTransaksi[Proses Transaksi]
    end

    subgraph Database ["🗄️ Database"]
        SimpanTrans[Simpan Transaksi]
        UpdateStok[Update Stok]
    end

    %% Flow connections
    Start --> MulaiTrans
    MulaiTrans --> ScanProd
    ScanProd --> ValidasiProduk
    ValidasiProduk --> InputJumlah
    InputJumlah --> HitungTotal
    HitungTotal --> KonfirmasiBayar
    KonfirmasiBayar --> ProsesTransaksi
    ProsesTransaksi --> SimpanTrans
    SimpanTrans --> UpdateStok
    UpdateStok --> CetakStruk
    CetakStruk --> End

    %% Styling
    classDef pegawaiStyle fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000
    classDef sistemStyle fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px,color:#000
    classDef databaseStyle fill:#FFECB3,stroke:#F57C00,stroke-width:2px,color:#000
    classDef startEndStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:3px,color:#000

    class MulaiTrans,ScanProd,InputJumlah,KonfirmasiBayar,CetakStruk pegawaiStyle
    class ValidasiProduk,HitungTotal,ProsesTransaksi sistemStyle
    class SimpanTrans,UpdateStok databaseStyle
    class Start,End startEndStyle
```

## Penjelasan Activity Diagram (Simplified)

### 🎯 **Tujuan**
Menggambarkan alur utama proses transaksi penjualan dengan fokus pada langkah-langkah inti dan pemisahan tanggung jawab yang jelas.

### 👥 **Swimlane Aktor**
- **👤 Pegawai**: Interaksi langsung dengan pelanggan dan sistem
- **💻 Sistem POS**: Validasi dan pemrosesan business logic
- **🗄️ Database**: Penyimpanan dan update data

### 🔄 **Alur Utama (Happy Path)**
1. **Pegawai**: Mulai transaksi → Scan produk → Input jumlah → Konfirmasi pembayaran → Cetak struk
2. **Sistem**: Validasi produk & stok → Hitung total → Proses transaksi
3. **Database**: Simpan transaksi → Update stok

### ✨ **Simplifikasi yang Dilakukan**
- **Fokus pada alur utama**: Menghilangkan exception handling yang kompleks
- **Menggabungkan aktivitas terkait**: Validasi produk dan stok dalam satu step
- **Streamlined flow**: Linear flow tanpa loop yang rumit
- **Cleaner presentation**: Lebih mudah dipahami stakeholder non-teknis

### 📊 **Output**
- Transaksi berhasil diproses
- Data tersimpan dengan benar
- Struk tercetak untuk pelanggan
