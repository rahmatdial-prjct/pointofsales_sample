# Activity Diagram - Proses Login & Authorization (Simplified)

```mermaid
flowchart TD
    subgraph User ["👤 User"]
        Start([🟢 Mulai])
        InputKredensial[Input Kredensial Login]
        AksesDashboard[Akses Dashboard]
        End([🔴 Selesai])
    end
    
    subgraph SistemPOS ["💻 Sistem POS"]
        ValidasiUser[Validasi User & Password]
        TentukanRole[Tentukan Role & Permission]
        LoadDashboard[Load Dashboard Sesuai Role]
    end
    
    subgraph Database ["🗄️ Database"]
        VerifikasiKredensial[Verifikasi Kredensial]
        SimpanSession[Simpan Session]
        LogAktivitas[Log Aktivitas]
    end
    
    %% Flow connections
    Start --> InputKredensial
    InputKredensial --> ValidasiUser
    ValidasiUser --> VerifikasiKredensial
    VerifikasiKredensial --> TentukanRole
    TentukanRole --> SimpanSession
    SimpanSession --> LogAktivitas
    LogAktivitas --> LoadDashboard
    LoadDashboard --> AksesDashboard
    AksesDashboard --> End

    %% Styling
    classDef userStyle fill:#E3F2FD,stroke:#1976D2,stroke-width:2px,color:#000
    classDef sistemStyle fill:#F3E5F5,stroke:#7B1FA2,stroke-width:2px,color:#000
    classDef databaseStyle fill:#FFECB3,stroke:#F57C00,stroke-width:2px,color:#000
    classDef startEndStyle fill:#E8F5E8,stroke:#2E7D32,stroke-width:3px,color:#000

    class InputKredensial,AksesDashboard userStyle
    class ValidasiUser,TentukanRole,LoadDashboard sistemStyle
    class VerifikasiKredensial,SimpanSession,LogAktivitas databaseStyle
    class Start,End startEndStyle
```

## Penjelasan Activity Diagram (Simplified)

### 🎯 **Tujuan**
Menggambarkan alur utama proses login dan authorization dengan fokus pada happy path.

### 👥 **Swimlane Aktor**
- **👤 User**: Input kredensial dan akses dashboard
- **💻 Sistem POS**: Validasi dan set permission
- **🗄️ Database**: Verifikasi dan logging

### 🔄 **Alur Utama**
1. **User**: Input username/password
2. **Sistem**: Validasi dan tentukan role/permission
3. **Database**: Verifikasi, simpan session, log aktivitas
4. **User**: Akses dashboard sesuai role

### ✨ **Role & Permission**
- **Direktur**: Full access semua cabang
- **Manajer**: Branch access dengan approval rights
- **Pegawai**: Limited access untuk transaksi

### 📊 **Output**
- Session aktif dengan permission yang sesuai
- Dashboard loaded sesuai role
- Aktivitas login tercatat
