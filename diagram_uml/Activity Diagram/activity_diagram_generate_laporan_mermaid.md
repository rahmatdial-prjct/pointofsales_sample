# Activity Diagram - Generate Laporan (Simplified)

```mermaid
flowchart TD
    subgraph User ["👤 User (Direktur/Manajer)"]
        Start([🟢 Mulai])
        PilihJenis[Pilih Jenis Laporan]
        SetParameter[Set Parameter & Filter]
        PreviewLaporan[Preview Laporan]
        DownloadPrint[Download/Print Laporan]
        End([🔴 Selesai])
    end
    
    subgraph SistemPOS ["💻 Sistem POS"]
        CekPermission[Cek Permission User]
        ProsesQuery[Proses Query Data]
        GenerateLaporan[Generate Laporan]
        FormatOutput[Format Output]
    end
    
    subgraph Database ["🗄️ Database"]
        QueryData[Query Data Sesuai Filter]
        LogAktivitas[Log Aktivitas Generate]
    end
    
    %% Flow connections
    Start --> PilihJenis
    PilihJenis --> CekPermission
    CekPermission --> SetParameter
    SetParameter --> ProsesQuery
    ProsesQuery --> QueryData
    QueryData --> GenerateLaporan
    GenerateLaporan --> FormatOutput
    FormatOutput --> PreviewLaporan
    PreviewLaporan --> DownloadPrint
    DownloadPrint --> LogAktivitas
    LogAktivitas --> End

    %% Styling
    classDef userStyle fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000
    classDef sistemStyle fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px,color:#000
    classDef databaseStyle fill:#FFECB3,stroke:#F57C00,stroke-width:2px,color:#000
    classDef startEndStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:3px,color:#000

    class PilihJenis,SetParameter,PreviewLaporan,DownloadPrint userStyle
    class CekPermission,ProsesQuery,GenerateLaporan,FormatOutput sistemStyle
    class QueryData,LogAktivitas databaseStyle
    class Start,End startEndStyle
```

## Penjelasan Activity Diagram (Simplified)

### 🎯 **Tujuan**
Menggambarkan alur utama generate laporan dengan fokus pada proses inti.

### 👥 **Swimlane Aktor**
- **👤 User (Direktur/Manajer)**: Pilih dan konfigurasi laporan
- **💻 Sistem POS**: Proses dan generate laporan
- **🗄️ Database**: Query data dan logging

### 🔄 **Jenis Laporan**
- **Laporan Penjualan**: Data transaksi dan revenue
- **Laporan Stok**: Status dan pergerakan stok
- **Laporan Return**: Data return dan alasan
- **Laporan Keuangan**: Analisa profit dan trend

### ✨ **Permission Level**
- **Direktur**: Akses semua cabang dan laporan
- **Manajer**: Akses cabang sendiri saja

### 📊 **Output**
- Laporan dalam format PDF/Excel
- Data sesuai filter dan permission
- Log aktivitas generate tercatat
