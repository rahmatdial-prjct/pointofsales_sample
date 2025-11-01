# Activity Diagram - Manajemen Stok Produk (Simplified)

```mermaid
flowchart TD
    subgraph Manajer ["👨‍💼 Manajer"]
        Start([🟢 Mulai])
        AksesDashboard[Akses Dashboard Stok]
        KelolaProduk[Input/Edit Data Produk]
        UpdateStok[Adjust Stok Produk]
        MonitorStok[Review Low Stock]
        GenerateLaporan[Generate Laporan]
        Selesai[Selesai]
        End([🔴 Selesai])
        PilihAktivitas{Pilih Aktivitas?}
    end
    
    subgraph SistemPOS ["💻 Sistem POS"]
        TampilkanDashboard[Tampilkan Dashboard]
        ValidasiProses[Validasi & Proses Data]
        UpdateNotifikasi[Update Notifikasi]
    end
    
    subgraph Database ["🗄️ Database"]
        SimpanPerubahan[Simpan Perubahan]
        UpdateRiwayat[Update Riwayat Stok]
    end
    
    %% Flow connections
    Start --> AksesDashboard
    AksesDashboard --> TampilkanDashboard
    TampilkanDashboard --> PilihAktivitas
    
    PilihAktivitas -->|Kelola Produk| KelolaProduk
    PilihAktivitas -->|Update Stok| UpdateStok
    PilihAktivitas -->|Monitor Stok| MonitorStok
    
    KelolaProduk --> ValidasiProses
    UpdateStok --> ValidasiProses
    MonitorStok --> GenerateLaporan
    GenerateLaporan --> ValidasiProses
    
    ValidasiProses --> SimpanPerubahan
    SimpanPerubahan --> UpdateRiwayat
    UpdateRiwayat --> UpdateNotifikasi
    UpdateNotifikasi --> Selesai
    Selesai --> End

    %% Styling
    classDef manajerStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:2px,color:#000
    classDef sistemStyle fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px,color:#000
    classDef databaseStyle fill:#FFECB3,stroke:#F57C00,stroke-width:2px,color:#000
    classDef decisionStyle fill:#FFF3E0,stroke:#F57C00,stroke-width:2px,color:#000
    classDef startEndStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:3px,color:#000

    class AksesDashboard,KelolaProduk,UpdateStok,MonitorStok,GenerateLaporan,Selesai manajerStyle
    class TampilkanDashboard,ValidasiProses,UpdateNotifikasi sistemStyle
    class SimpanPerubahan,UpdateRiwayat databaseStyle
    class PilihAktivitas decisionStyle
    class Start,End startEndStyle
```

## Penjelasan Activity Diagram (Simplified)

### 🎯 **Tujuan**
Menggambarkan alur utama manajemen stok produk dengan fokus pada aktivitas inti manajer.

### 👥 **Swimlane Aktor**
- **👨‍💼 Manajer**: Mengelola produk dan stok
- **💻 Sistem POS**: Validasi dan pemrosesan
- **🗄️ Database**: Penyimpanan data

### 🔄 **Aktivitas Utama**
1. **Kelola Produk**: Input/edit data produk baru atau existing
2. **Update Stok**: Adjust stok (tambah/kurangi/set manual)
3. **Monitor Stok**: Review low stock dan generate laporan

### ✨ **Simplifikasi yang Dilakukan**
- **Menggabungkan aktivitas serupa**: Semua jenis update stok dalam satu step
- **Fokus pada decision utama**: Pilihan aktivitas utama saja
- **Streamlined flow**: Linear flow untuk setiap path
- **Cleaner presentation**: Mudah dipahami untuk stakeholder

### 📊 **Output**
- Data produk dan stok terupdate
- Riwayat perubahan tercatat
- Notifikasi low stock terupdate
