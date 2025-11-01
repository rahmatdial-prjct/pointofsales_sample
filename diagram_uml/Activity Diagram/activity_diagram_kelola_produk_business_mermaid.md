# Activity Diagram - Kelola Produk Baju Muslim (Business Layer)

```mermaid
flowchart TD
    subgraph Manajer ["👤 Manajer"]
        Start([🟢 Mulai])
        TerimaProduk[Terima produk baru<br/>dari supplier]
        PeriksaKualitas[Periksa kualitas produk<br/>hijab/gamis/mukena]
        TentukanKategori[Tentukan kategori produk]
        Decision{Produk sesuai standar?}
        InputData[Input data produk<br/>ke sistem]
        UploadFoto[Upload foto produk]
        TentukanHarga[Tentukan harga jual]
        CetakLabel[Cetak label harga]
        AturDisplay[Atur display produk<br/>di toko]
        KembalikanProduk[Kembalikan produk<br/>ke supplier]
        BuatLaporan[Buat laporan<br/>ketidaksesuaian]
        End([🔴 Selesai])
    end

    subgraph SistemPOS ["💻 Sistem POS"]
        ValidasiData[Validasi data produk]
        GenerateSKU[Generate kode SKU]
        SimpanData[Simpan data produk]
    end

    subgraph Database ["🗄️ Database"]
        TambahStok[Tambah stok produk]
        UpdateKatalog[Update katalog produk]
    end

    %% Flow connections
    Start --> TerimaProduk
    TerimaProduk --> PeriksaKualitas
    PeriksaKualitas --> TentukanKategori
    TentukanKategori --> Decision
    
    Decision -->|Ya| InputData
    Decision -->|Tidak| KembalikanProduk
    
    InputData --> UploadFoto
    UploadFoto --> TentukanHarga
    TentukanHarga --> ValidasiData
    ValidasiData --> GenerateSKU
    GenerateSKU --> SimpanData
    SimpanData --> CetakLabel
    CetakLabel --> AturDisplay
    AturDisplay --> TambahStok
    TambahStok --> UpdateKatalog
    UpdateKatalog --> End
    
    KembalikanProduk --> BuatLaporan
    BuatLaporan --> End

    %% Styling
    classDef manajer fill:#FFECB3,stroke:#F57C00,stroke-width:2px
    classDef sistem fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px
    classDef database fill:#E8F5E8,stroke:#388E3C,stroke-width:2px
    classDef decision fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px
    classDef start fill:#C8E6C9,stroke:#388E3C,stroke-width:2px
    classDef end fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px

    class TerimaProduk,PeriksaKualitas,TentukanKategori,InputData,UploadFoto,TentukanHarga,CetakLabel,AturDisplay,KembalikanProduk,BuatLaporan manajer
    class ValidasiData,GenerateSKU,SimpanData sistem
    class TambahStok,UpdateKatalog database
    class Decision decision
    class Start start
    class End end
```

## Penjelasan Activity Diagram - Kelola Produk Baju Muslim

### 🎯 **Fokus Bisnis**
Diagram ini menggambarkan alur pengelolaan produk baju muslim dari penerimaan supplier hingga siap dijual di toko.

### 👥 **Aktor yang Terlibat**
- **👤 Manajer**: Pengelola cabang yang bertanggung jawab atas produk
- **💻 Sistem POS**: Sistem untuk mencatat dan mengelola data produk
- **🗄️ Database**: Penyimpanan data produk dan stok

### 🔄 **Alur Proses Bisnis**

#### **1. Penerimaan Produk (Manajer)**
- Menerima kiriman produk baru dari supplier
- Memeriksa kualitas fisik produk (jahitan, bahan, warna)
- Menentukan kategori produk sesuai jenis (hijab, gamis, mukena)
- Melakukan quality control berdasarkan standar toko

#### **2. Input Data Produk (Manajer)**
- Menginput informasi produk ke sistem (nama, deskripsi, ukuran)
- Mengupload foto produk untuk katalog online
- Menentukan harga jual berdasarkan harga beli dan margin keuntungan
- Memastikan data produk lengkap dan akurat

#### **3. Proses Sistem (Sistem POS)**
- Memvalidasi kelengkapan data produk
- Menggenerate kode SKU unik untuk setiap produk
- Menyimpan data produk ke dalam database
- Mengintegrasikan dengan sistem inventory

#### **4. Finalisasi Produk (Manajer)**
- Mencetak label harga untuk ditempel pada produk
- Mengatur display produk di area penjualan toko
- Memastikan produk mudah ditemukan oleh pelanggan
- Mengupdate katalog produk untuk referensi pegawai

#### **5. Update Database**
- Menambahkan stok produk baru ke inventory
- Mengupdate katalog produk yang tersedia
- Menyinkronkan data dengan sistem pusat

### ⚠️ **Skenario Alternatif**
- Jika produk tidak sesuai standar kualitas, manajer akan mengembalikan produk ke supplier
- Membuat laporan ketidaksesuaian untuk evaluasi supplier
- Proses dapat diulang dengan produk pengganti

### 📦 **Kategori Produk Muslim**
- **Hijab**: Berbagai model dan warna sesuai trend
- **Gamis**: Untuk acara casual maupun formal
- **Mukena**: Untuk dewasa dan anak-anak
- **Aksesoris**: Pelengkap busana muslim

### 📊 **Manfaat Bisnis**
- Kontrol kualitas produk yang konsisten
- Pencatatan inventory yang akurat
- Harga yang kompetitif dan menguntungkan
- Display produk yang menarik untuk meningkatkan penjualan
