# Activity Diagram - Proses Retur Produk (Business Layer)

```mermaid
flowchart TD
    subgraph Pegawai ["👤 Pegawai"]
        Start([🟢 Mulai])
        TerimaKeluhan[Terima keluhan pelanggan]
        PeriksaKondisi[Periksa kondisi produk<br/>rusak/cacat/salah ukuran]
        CekStruk[Cek struk pembelian]
        Decision1{Produk layak retur?}
        InputRetur[Input data retur<br/>ke sistem]
        FotoProduk[Foto kondisi produk]
        ProsesKembali[Proses pengembalian uang<br/>atau tukar produk]
        CetakBukti[Cetak bukti retur]
        JelaskanPenolakan[Jelaskan alasan penolakan<br/>kepada pelanggan]
        JelaskanKebijakan[Jelaskan kebijakan retur<br/>kepada pelanggan]
        End([🔴 Selesai])
    end

    subgraph SistemPOS ["💻 Sistem POS"]
        BuatRetur[Buat permintaan retur]
        KirimNotif[Kirim notifikasi<br/>ke manajer]
        UpdateSetuju[Update status retur<br/>"Disetujui"]
        UpdateTolak[Update status retur<br/>"Ditolak"]
    end

    subgraph Manajer ["👤 Manajer"]
        TerimaNotif[Terima notifikasi retur]
        ReviewKondisi[Review kondisi produk]
        PeriksaAlasan[Periksa alasan retur]
        Decision2{Setujui retur?}
        SetujuiRetur[Setujui permintaan retur]
        TolakRetur[Tolak permintaan retur]
    end

    subgraph Database ["🗄️ Database"]
        SimpanRetur[Simpan data retur]
        TambahStok[Tambah stok produk<br/>jika masih layak jual]
    end

    %% Flow connections
    Start --> TerimaKeluhan
    TerimaKeluhan --> PeriksaKondisi
    PeriksaKondisi --> CekStruk
    CekStruk --> Decision1
    
    Decision1 -->|Ya| InputRetur
    Decision1 -->|Tidak| JelaskanKebijakan
    JelaskanKebijakan --> End
    
    InputRetur --> FotoProduk
    FotoProduk --> BuatRetur
    BuatRetur --> KirimNotif
    KirimNotif --> TerimaNotif
    TerimaNotif --> ReviewKondisi
    ReviewKondisi --> PeriksaAlasan
    PeriksaAlasan --> Decision2
    
    Decision2 -->|Ya| SetujuiRetur
    Decision2 -->|Tidak| TolakRetur
    
    SetujuiRetur --> UpdateSetuju
    UpdateSetuju --> ProsesKembali
    ProsesKembali --> CetakBukti
    CetakBukti --> SimpanRetur
    SimpanRetur --> TambahStok
    TambahStok --> End
    
    TolakRetur --> UpdateTolak
    UpdateTolak --> JelaskanPenolakan
    JelaskanPenolakan --> End

    %% Styling
    classDef pegawai fill:#E3F2FD,stroke:#1976D2,stroke-width:2px
    classDef sistem fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px
    classDef manajer fill:#FFECB3,stroke:#F57C00,stroke-width:2px
    classDef database fill:#E8F5E8,stroke:#388E3C,stroke-width:2px
    classDef decision fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px
    classDef start fill:#C8E6C9,stroke:#388E3C,stroke-width:2px
    classDef end fill:#FFCDD2,stroke:#D32F2F,stroke-width:2px

    class TerimaKeluhan,PeriksaKondisi,CekStruk,InputRetur,FotoProduk,ProsesKembali,CetakBukti,JelaskanPenolakan,JelaskanKebijakan pegawai
    class BuatRetur,KirimNotif,UpdateSetuju,UpdateTolak sistem
    class TerimaNotif,ReviewKondisi,PeriksaAlasan,SetujuiRetur,TolakRetur manajer
    class SimpanRetur,TambahStok database
    class Decision1,Decision2 decision
    class Start start
    class End end
```

## Penjelasan Activity Diagram - Proses Retur Produk

### 🎯 **Fokus Bisnis**
Diagram ini menggambarkan alur proses retur produk baju muslim dari perspektif operasional toko yang melibatkan pegawai dan manajer.

### 👥 **Aktor yang Terlibat**
- **👤 Pegawai**: Staff toko yang menerima keluhan dan memproses retur
- **👤 Manajer**: Supervisor yang menyetujui/menolak permintaan retur
- **💻 Sistem POS**: Sistem untuk mencatat dan mengelola proses retur
- **🗄️ Database**: Penyimpanan data retur dan update stok

### 🔄 **Alur Proses Bisnis**

#### **1. Penerimaan Keluhan (Pegawai)**
- Menerima keluhan pelanggan tentang produk
- Memeriksa kondisi fisik produk (rusak/cacat/salah ukuran)
- Mengecek struk pembelian sebagai bukti transaksi
- Menentukan kelayakan retur berdasarkan kebijakan toko

#### **2. Pengajuan Retur (Pegawai)**
- Menginput data retur ke dalam sistem
- Mengambil foto kondisi produk sebagai dokumentasi
- Sistem otomatis membuat permintaan retur
- Mengirim notifikasi ke manajer untuk persetujuan

#### **3. Review dan Persetujuan (Manajer)**
- Menerima notifikasi permintaan retur
- Mereview kondisi produk berdasarkan foto dan deskripsi
- Memeriksa alasan retur apakah sesuai kebijakan
- Memutuskan untuk menyetujui atau menolak retur

#### **4. Finalisasi Proses**
**Jika Disetujui:**
- Pegawai memproses pengembalian uang atau tukar produk
- Mencetak bukti retur untuk pelanggan
- Sistem menyimpan data retur dan menambah stok (jika produk masih layak jual)

**Jika Ditolak:**
- Pegawai menjelaskan alasan penolakan kepada pelanggan
- Sistem mencatat penolakan untuk dokumentasi

### ⚠️ **Kriteria Retur**
- Produk dalam kondisi rusak atau cacat produksi
- Salah ukuran yang bukan kesalahan pelanggan
- Memiliki struk pembelian yang valid
- Masih dalam batas waktu retur yang ditetapkan toko

### 📊 **Manfaat Bisnis**
- Proses retur yang terstandar dan terdokumentasi
- Kontrol kualitas produk melalui tracking retur
- Kepuasan pelanggan melalui pelayanan retur yang baik
- Data retur untuk evaluasi supplier dan produk
