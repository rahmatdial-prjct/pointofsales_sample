# Activity Diagram - Proses Return Barang (Simplified)

```mermaid
flowchart TD
    subgraph Pegawai ["👤 Pegawai"]
        Start([🟢 Mulai])
        TerimaReturn[Terima Permintaan Return]
        ValidasiStruk[Validasi Struk & Kondisi Barang]
        InputData[Input Data Return]
        InfoApproval[Informasikan Menunggu Approval]
        ProsesReturn[Proses Return Langsung]
        SelesaiReturn[Selesaikan Return]
        End([🔴 Selesai])
    end

    subgraph Manajer ["👨‍💼 Manajer"]
        ReviewApprove[Review & Approve Return]
    end

    subgraph SistemPOS ["💻 Sistem POS"]
        ValidasiTransaksi[Validasi Transaksi Asli]
        HitungNilai[Hitung Nilai Return]
        ProsesReturnSys[Proses Return]
        PerluApproval{Perlu Approval?}
    end

    subgraph Database ["🗄️ Database"]
        UpdateReturn[Update Data Return]
        KembalikanStok[Kembalikan Stok]
    end

    %% Flow connections
    Start --> TerimaReturn
    TerimaReturn --> ValidasiStruk
    ValidasiStruk --> ValidasiTransaksi
    ValidasiTransaksi --> InputData
    InputData --> HitungNilai
    HitungNilai --> PerluApproval

    PerluApproval -->|Ya| InfoApproval
    InfoApproval --> ReviewApprove
    ReviewApprove --> ProsesReturnSys

    PerluApproval -->|Tidak| ProsesReturn
    ProsesReturn --> ProsesReturnSys

    ProsesReturnSys --> UpdateReturn
    UpdateReturn --> KembalikanStok
    KembalikanStok --> SelesaiReturn
    SelesaiReturn --> End

    %% Styling
    classDef pegawaiStyle fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000
    classDef manajerStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:2px,color:#000
    classDef sistemStyle fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px,color:#000
    classDef databaseStyle fill:#FFECB3,stroke:#F57C00,stroke-width:2px,color:#000
    classDef decisionStyle fill:#FFF3E0,stroke:#F57C00,stroke-width:2px,color:#000
    classDef startEndStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:3px,color:#000

    class TerimaReturn,ValidasiStruk,InputData,InfoApproval,ProsesReturn,SelesaiReturn pegawaiStyle
    class ReviewApprove manajerStyle
    class ValidasiTransaksi,HitungNilai,ProsesReturnSys sistemStyle
    class UpdateReturn,KembalikanStok databaseStyle
    class PerluApproval decisionStyle
    class Start,End startEndStyle
```

## Penjelasan Activity Diagram (Simplified)

### 🎯 **Tujuan**
Menggambarkan alur utama proses return barang dengan fokus pada langkah-langkah inti dan approval workflow.

### 👥 **Swimlane Aktor**
- **👤 Pegawai**: Interaksi dengan pelanggan dan input data
- **👨‍💼 Manajer**: Review dan approval return
- **💻 Sistem POS**: Validasi dan pemrosesan business logic
- **🗄️ Database**: Update data dan stok

### 🔄 **Alur Utama (Happy Path)**
1. **Pegawai**: Terima permintaan → Validasi struk & barang → Input data return
2. **Sistem**: Validasi transaksi asli → Hitung nilai return → Tentukan perlu approval
3. **Manajer**: Review dan approve (jika diperlukan)
4. **Database**: Update data return → Kembalikan stok

### ✨ **Simplifikasi yang Dilakukan**
- **Menggabungkan validasi**: Struk dan kondisi barang dalam satu step
- **Streamlined approval**: Fokus pada decision utama perlu approval atau tidak
- **Linear flow**: Menghilangkan multiple exception paths
- **Cleaner presentation**: Mudah dipahami untuk training dan SOP

### 📊 **Output**
- Return berhasil diproses
- Stok dikembalikan ke inventory
- Data return tercatat dengan benar
