# Sequence Diagram - Generate Laporan

```mermaid
sequenceDiagram
    participant U as 👤 User (Direktur/Manajer)
    participant S as 💻 Sistem POS
    participant D as 🗄️ Database

    Note over U,D: Generate Laporan

    U->>S: 1. Akses Menu Laporan
    S->>S: 2. Cek Role & Permission User
    
    alt Role = Direktur
        S->>S: 3. Set Filter = Semua Cabang
        S->>U: 4. Tampilkan Semua Jenis Laporan
        
    else Role = Manajer
        S->>S: 5. Set Filter = Cabang User
        S->>U: 6. Tampilkan Laporan Cabang
        
    else Role = Pegawai
        S->>U: 7. Tampilkan Laporan Terbatas (View Only)
    end
    
    U->>S: 8. Pilih Jenis Laporan
    Note right of U: Penjualan, Stok, Return, Keuangan
    
    U->>S: 9. Set Parameter & Filter
    Note right of U: Periode, Kategori, Status, Format Output
    
    S->>S: 10. Validasi Parameter Input
    
    alt Parameter Valid
        S->>D: 11. Query Data Sesuai Filter & Permission
        
        alt Laporan Penjualan
            D->>D: 12. Query Transaksi & Detail
            D->>S: 13. Return Data Penjualan
            
        else Laporan Stok
            D->>D: 14. Query Produk & Stok Current
            D->>S: 15. Return Data Stok
            
        else Laporan Return
            D->>D: 16. Query Return & Status
            D->>S: 17. Return Data Return
            
        else Laporan Keuangan
            D->>D: 18. Query Transaksi & Return untuk Analisa
            D->>S: 19. Return Data Keuangan
        end
        
        S->>S: 20. Proses & Kalkulasi Data
        S->>S: 21. Generate Chart/Grafik
        S->>S: 22. Format Data Sesuai Template
        S->>U: 23. Preview Laporan
        
        U->>S: 24. Konfirmasi Generate Final
        S->>S: 25. Generate File Final (PDF/Excel)
        S->>S: 26. Simpan ke Temporary Storage
        
        alt Download
            U->>S: 27. Request Download
            S->>U: 28. Download File Laporan
            
        else Email
            U->>S: 29. Input Email Tujuan
            S->>S: 30. Validasi Email Format
            S->>S: 31. Send Email dengan Attachment
            S->>U: 32. Konfirmasi Email Terkirim
            
        else Print
            U->>S: 33. Request Print
            S->>S: 34. Format untuk Print
            S->>U: 35. Print Laporan
        end
        
        S->>D: 36. Log Aktivitas Generate Laporan
        D->>S: 37. Konfirmasi Log
        
        S->>S: 38. Cleanup Temporary Files
        S->>U: 39. Laporan Berhasil Diproses
        
    else Parameter Invalid
        S->>U: 40. Error: Parameter Tidak Valid
    end

    Note over U,D: Generate Berhasil: File tersedia, Data sesuai filter, Log tercatat
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi antar komponen dalam proses generate laporan dengan role-based access dan berbagai jenis output.

### 👥 **Participants**
- **👤 User (Direktur/Manajer)**: User yang memiliki akses untuk generate laporan
- **💻 Sistem POS**: Aplikasi yang memproses logic reporting
- **🗄️ Database**: Penyimpanan data untuk berbagai jenis laporan

### 🔄 **Alur Interaksi**
1. **Access Control**: User akses menu → System cek role → Set permission filter
2. **Report Selection**: Pilih jenis laporan → Set parameter dan filter
3. **Data Processing**: Query database → Proses data → Generate chart/grafik
4. **Preview & Confirm**: Tampilkan preview → User konfirmasi → Generate final
5. **Output Delivery**: Download/Email/Print sesuai pilihan user
6. **Cleanup**: Log aktivitas → Cleanup temporary files

### ⚠️ **Role-based Access**
- **Direktur**: Akses semua cabang dan semua jenis laporan
- **Manajer**: Akses cabang sendiri dengan laporan lengkap
- **Pegawai**: Akses terbatas (view only) untuk laporan tertentu

### 📊 **Jenis Laporan**
- **Laporan Penjualan**: Data transaksi, revenue, trend penjualan
- **Laporan Stok**: Current stock, movement, low stock alerts
- **Laporan Return**: Return data, reasons, approval status
- **Laporan Keuangan**: Financial analysis, profit margin, net revenue

### 💾 **Database Queries**
- **Dynamic Filtering**: Query berdasarkan role, periode, dan parameter
- **Complex Joins**: Multiple table joins untuk comprehensive data
- **Aggregation**: Sum, count, average untuk statistical analysis
- **Performance Optimization**: Efficient queries untuk large datasets

### 📈 **Output Options**
- **Download**: PDF/Excel file untuk offline analysis
- **Email**: Send laporan via email dengan attachment
- **Print**: Direct printing untuk hard copy
- **Preview**: Online preview sebelum generate final

### 🔒 **Security & Audit**
- **Permission Filtering**: Data dibatasi sesuai role dan cabang
- **Activity Logging**: Semua generate activity tercatat
- **Temporary File Management**: Secure cleanup setelah delivery
- **Access Control**: Validasi permission di setiap step

### 📊 **Output**
- File laporan dalam format yang dipilih (PDF/Excel)
- Data yang akurat sesuai filter dan permission
- Chart dan grafik untuk visualisasi data
- Log aktivitas untuk audit trail
