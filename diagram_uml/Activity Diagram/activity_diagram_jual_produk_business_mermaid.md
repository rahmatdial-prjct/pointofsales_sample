# Activity Diagram - Jual Produk Baju Muslim (Business Layer)

```mermaid
flowchart TD
    subgraph Pegawai ["👤 Pegawai"]
        Start([🟢 Mulai])
        TerimaPelanggan[Terima pelanggan]
        TanyaKebutuhan[Tanyakan kebutuhan<br/>hijab/gamis/mukena]
        CariProduk[Cari produk di katalog]
        TunjukkanProduk[Tunjukkan produk<br/>kepada pelanggan]
        Decision{Pelanggan setuju?}
        InputNama[Input nama pelanggan]
        InputJumlah[Masukkan jumlah produk]
        PilihBayar[Pilih metode pembayaran<br/>tunai/transfer/QRIS]
        CetakStruk[Cetak struk penjualan]
        SerahkanProduk[Serahkan produk<br/>dan struk]
        TawarLain[Tawarkan produk lain]
        End([🔴 Selesai])
        Stop([❌ Batal])
    end

    subgraph SistemPOS ["💻 Sistem POS"]
        TampilkanProduk[Tampilkan daftar produk<br/>yang tersedia]
        CekStok[Cek stok produk]
        HitungTotal[Hitung total harga]
        ProsesTransaksi[Proses transaksi]
    end

    subgraph Database ["🗄️ Database"]
        SimpanTransaksi[Simpan data transaksi]
        KurangiStok[Kurangi stok produk]
    end

    %% Flow connections
    Start --> TerimaPelanggan
    TerimaPelanggan --> TanyaKebutuhan
    TanyaKebutuhan --> CariProduk
    CariProduk --> TampilkanProduk
    TampilkanProduk --> CekStok
    CekStok --> TunjukkanProduk
    TunjukkanProduk --> Decision
    
    Decision -->|Ya| InputNama
    Decision -->|Tidak| TawarLain
    TawarLain --> Stop
    
    InputNama --> InputJumlah
    InputJumlah --> PilihBayar
    PilihBayar --> HitungTotal
    HitungTotal --> ProsesTransaksi
    ProsesTransaksi --> CetakStruk
    CetakStruk --> SerahkanProduk
    SerahkanProduk --> SimpanTransaksi
    SimpanTransaksi --> KurangiStok
    KurangiStok --> End

    %% Styling
    classDef pegawai fill:#E3F2FD,stroke:#1976D2,stroke-width:2px
    classDef sistem fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px
    classDef database fill:#FFECB3,stroke:#F57C00,stroke-width:2px
    classDef decision fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px
    classDef start fill:#C8E6C9,stroke:#388E3C,stroke-width:2px
    classDef end fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px

    class TerimaPelanggan,TanyaKebutuhan,CariProduk,TunjukkanProduk,InputNama,InputJumlah,PilihBayar,CetakStruk,SerahkanProduk,TawarLain pegawai
    class TampilkanProduk,CekStok,HitungTotal,ProsesTransaksi sistem
    class SimpanTransaksi,KurangiStok database
    class Decision decision
    class Start start
    class End,Stop end
```

## Penjelasan Activity Diagram - Jual Produk Baju Muslim

### 🎯 **Fokus Bisnis**
Diagram ini menggambarkan alur penjualan produk baju muslim dari perspektif bisnis yang dapat dipahami oleh pemilik toko dan staff.

### 👥 **Aktor yang Terlibat**
- **👤 Pegawai**: Staff toko yang melayani pelanggan
- **💻 Sistem POS**: Sistem kasir untuk memproses transaksi
- **🗄️ Database**: Penyimpanan data transaksi dan stok

### 🔄 **Alur Proses Bisnis**

#### **1. Pelayanan Pelanggan (Pegawai)**
- Menyambut pelanggan yang datang ke toko
- Menanyakan kebutuhan (hijab, gamis, mukena, dll)
- Mencari produk yang sesuai di katalog
- Menunjukkan produk kepada pelanggan

#### **2. Sistem Pendukung (Sistem POS)**
- Menampilkan daftar produk yang tersedia
- Mengecek stok produk secara real-time
- Menghitung total harga pembelian
- Memproses transaksi pembayaran

#### **3. Finalisasi Transaksi**
- Input data pelanggan dan jumlah produk
- Pilih metode pembayaran (tunai/transfer/QRIS)
- Cetak struk penjualan
- Serahkan produk dan struk kepada pelanggan

#### **4. Pencatatan Data (Database)**
- Menyimpan data transaksi untuk laporan
- Mengurangi stok produk yang terjual

### ⚠️ **Skenario Alternatif**
- Jika pelanggan tidak setuju dengan produk yang ditawarkan, pegawai akan menawarkan produk alternatif lain
- Proses dapat dibatalkan jika tidak ada produk yang sesuai

### 📊 **Manfaat Bisnis**
- Pelayanan pelanggan yang terstruktur
- Pencatatan transaksi yang akurat
- Kontrol stok yang real-time
- Dokumentasi penjualan untuk analisis bisnis
