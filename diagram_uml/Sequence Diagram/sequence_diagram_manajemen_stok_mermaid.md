# Sequence Diagram - Manajemen Stok Produk

```mermaid
sequenceDiagram
    participant M as 👨‍💼 Manajer
    participant S as 💻 Sistem POS
    participant D as 🗄️ Database

    Note over M,D: Manajemen Stok Produk

    M->>S: 1. Akses Dashboard Stok
    S->>D: 2. Query Data Stok & Produk
    D->>S: 3. Return Data Dashboard
    S->>S: 4. Highlight Produk Low Stock
    S->>M: 5. Tampilkan Dashboard Stok
    
    alt Kelola Produk
        M->>S: 6. Pilih Menu Kelola Produk
        
        alt Tambah Produk Baru
            M->>S: 7. Input Data Produk Baru
            Note right of M: Nama, SKU, Harga, Kategori, Stok Awal, Min Stok
            S->>S: 8. Validasi Data Input
            
            alt Data Valid
                S->>D: 9. Simpan Produk Baru
                D->>S: 10. Product ID
                S->>D: 11. Catat Riwayat Stok Awal
                D->>S: 12. Konfirmasi Riwayat
                S->>M: 13. Konfirmasi Produk Tersimpan
                
            else Data Invalid
                S->>M: 14. Error: Data Tidak Valid
            end
            
        else Edit Produk Existing
            M->>S: 15. Pilih Produk untuk Edit
            S->>D: 16. Query Detail Produk
            D->>S: 17. Return Detail Produk
            S->>M: 18. Tampilkan Form Edit
            
            M->>S: 19. Update Data Produk
            S->>S: 20. Validasi Perubahan
            S->>D: 21. Update Data Produk
            D->>S: 22. Konfirmasi Update
            S->>M: 23. Konfirmasi Perubahan Tersimpan
        end
        
    else Update Stok
        M->>S: 24. Pilih Menu Update Stok
        M->>S: 25. Pilih Produk
        S->>D: 26. Query Detail Produk & Riwayat Stok
        D->>S: 27. Return Data Produk & Riwayat
        S->>M: 28. Tampilkan Detail & Riwayat Stok
        
        M->>S: 29. Input Perubahan Stok
        Note right of M: Jenis: Tambah/Kurangi/Set Manual, Jumlah & Alasan
        S->>S: 30. Validasi Perubahan Stok
        
        alt Perubahan Valid
            S->>S: 31. Hitung Stok Baru
            S->>D: 32. Update Stok Produk
            D->>S: 33. Konfirmasi Update Stok
            S->>D: 34. Catat Riwayat Perubahan
            D->>S: 35. Konfirmasi Riwayat
            S->>M: 36. Konfirmasi Stok Terupdate
            
        else Perubahan Invalid
            S->>M: 37. Error: Perubahan Tidak Valid
        end
        
    else Monitor Stok
        M->>S: 38. Pilih Menu Monitor Stok
        S->>D: 39. Query Produk Low Stock
        D->>S: 40. Return Daftar Low Stock
        S->>M: 41. Tampilkan Daftar Low Stock
        
        M->>S: 42. Generate Laporan Stok
        S->>D: 43. Query Data untuk Laporan
        D->>S: 44. Return Data Laporan
        S->>S: 45. Generate Laporan (PDF/Excel)
        S->>M: 46. Tampilkan/Download Laporan
    end
    
    S->>D: 47. Update Dashboard & Notifikasi
    D->>S: 48. Konfirmasi Update
    S->>M: 49. Refresh Dashboard

    Note over M,D: Manajemen Berhasil: Data terupdate, Riwayat tercatat, Dashboard refresh
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi antar komponen dalam proses manajemen stok produk dengan berbagai aktivitas CRUD dan monitoring.

### 👥 **Participants**
- **👨‍💼 Manajer**: User yang memiliki akses penuh untuk mengelola produk dan stok
- **💻 Sistem POS**: Aplikasi yang memproses logic manajemen stok
- **🗄️ Database**: Penyimpanan data produk, stok, dan riwayat perubahan

### 🔄 **Alur Interaksi**
1. **Dashboard Access**: Manajer akses dashboard → System query data → Tampilkan overview
2. **Product Management**: CRUD operations untuk data produk
3. **Stock Updates**: Adjust stok dengan berbagai jenis perubahan
4. **Stock Monitoring**: Review low stock dan generate laporan
5. **History Tracking**: Catat semua perubahan untuk audit trail

### ⚠️ **Alternative Flows**
- **Kelola Produk**: Tambah baru vs Edit existing
- **Update Stok**: Tambah/Kurangi/Set Manual dengan validasi
- **Monitor Stok**: Low stock alerts dan laporan
- **Data Validation**: Input validation dengan error handling

### 📊 **Stock Management Operations**
- **Tambah Produk**: Input data lengkap → Validasi → Simpan + riwayat awal
- **Edit Produk**: Query existing → Update → Konfirmasi
- **Update Stok**: Pilih produk → Input perubahan → Validasi → Update + riwayat
- **Monitor**: Query low stock → Generate laporan → Download/view

### 💾 **Database Interactions**
- **Dashboard Query**: Real-time data untuk overview dan alerts
- **Product CRUD**: Create, Read, Update operations untuk master data
- **Stock Updates**: Atomic updates dengan history tracking
- **Report Generation**: Complex queries untuk analisa dan laporan

### 📈 **Monitoring Features**
- **Low Stock Alerts**: Automatic highlighting produk dengan stok < minimum
- **Stock History**: Complete audit trail untuk semua perubahan stok
- **Report Generation**: PDF/Excel export untuk analisa dan planning
- **Dashboard Refresh**: Real-time updates untuk monitoring

### 📊 **Output**
- Data produk dan stok terupdate secara real-time
- Riwayat lengkap semua perubahan stok
- Dashboard dengan notifikasi low stock
- Laporan stok untuk analisa dan decision making
