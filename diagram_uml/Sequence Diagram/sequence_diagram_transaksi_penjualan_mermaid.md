# Sequence Diagram - Proses Transaksi Penjualan

```mermaid
sequenceDiagram
    participant P as 👤 Pegawai
    participant S as 💻 Sistem POS
    participant D as 🗄️ Database

    Note over P,D: Proses Transaksi Penjualan

    P->>S: 1. Mulai Transaksi
    S->>S: 2. Inisialisasi Keranjang Kosong
    S->>P: 3. Tampilkan Interface Transaksi
    
    loop Untuk Setiap Produk
        P->>S: 4. Scan/Input Kode Produk
        S->>D: 5. Query Data Produk
        
        alt Produk Ditemukan
            D->>S: 6. Return Data Produk
            S->>D: 7. Cek Stok Tersedia
            
            alt Stok Mencukupi
                D->>S: 8. Konfirmasi Stok Available
                S->>P: 9. Tampilkan Info Produk
                
                P->>S: 10. Input Jumlah Beli
                S->>S: 11. Validasi Jumlah vs Stok
                
                alt Jumlah Valid
                    S->>S: 12. Hitung Subtotal
                    S->>S: 13. Tambah ke Keranjang
                    S->>S: 14. Update Total Belanja
                    S->>P: 15. Update Display Keranjang
                    
                else Jumlah Melebihi Stok
                    S->>P: 16. Error: Stok Tidak Cukup
                end
                
            else Stok Habis
                D->>S: 17. Stok Tidak Available
                S->>P: 18. Error: Stok Habis
            end
            
        else Produk Tidak Ditemukan
            D->>S: 19. Produk Not Found
            S->>P: 20. Error: Produk Tidak Ditemukan
        end
    end
    
    P->>S: 21. Konfirmasi Pembayaran
    S->>S: 22. Hitung Total Akhir
    S->>P: 23. Tampilkan Ringkasan Transaksi
    
    P->>S: 24. Konfirmasi Final
    S->>S: 25. Generate Nomor Invoice
    S->>D: 26. Simpan Data Transaksi
    D->>S: 27. Transaction ID
    
    S->>D: 28. Update Stok Produk (Batch)
    D->>S: 29. Konfirmasi Update Stok
    
    S->>S: 30. Generate Struk
    S->>P: 31. Tampilkan Struk untuk Cetak
    
    P->>S: 32. Cetak Struk
    S->>P: 33. Struk Tercetak
    S->>P: 34. Transaksi Selesai

    Note over P,D: Transaksi Berhasil: Invoice tersimpan, Stok terupdate, Struk tercetak
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi antar komponen dalam proses transaksi penjualan dari mulai scan produk hingga cetak struk.

### 👥 **Participants**
- **👤 Pegawai**: Staff yang melayani pelanggan dan mengoperasikan sistem
- **💻 Sistem POS**: Aplikasi yang memproses logic transaksi
- **🗄️ Database**: Penyimpanan data produk, stok, dan transaksi

### 🔄 **Alur Interaksi**
1. **Inisialisasi**: Pegawai mulai transaksi → System setup keranjang kosong
2. **Product Loop**: Scan produk → Validasi → Tambah ke keranjang (repeat)
3. **Validation**: Cek ketersediaan produk dan stok di database
4. **Calculation**: Hitung subtotal per item dan total keseluruhan
5. **Finalization**: Konfirmasi pembayaran → Generate invoice → Simpan transaksi
6. **Stock Update**: Update stok produk secara batch
7. **Receipt**: Generate dan cetak struk untuk pelanggan

### ⚠️ **Alternative Flows**
- **Produk Tidak Ditemukan**: Scan kode yang tidak ada di database
- **Stok Habis**: Produk ada tapi stok = 0
- **Jumlah Melebihi Stok**: Input quantity > available stock
- **Validasi Error**: Input yang tidak valid

### 🔁 **Loop Behavior**
- **Product Loop**: Pegawai dapat scan multiple produk
- **Error Recovery**: Sistem memberikan feedback error dan allow retry
- **Cart Management**: Real-time update keranjang dan total

### 💾 **Database Interactions**
- **Query Produk**: Real-time product lookup
- **Cek Stok**: Validasi ketersediaan inventory
- **Simpan Transaksi**: Atomic transaction save
- **Update Stok**: Batch update untuk performance

### 📊 **Output**
- Transaksi tersimpan dengan nomor invoice unik
- Stok produk terupdate secara real-time
- Struk tercetak untuk pelanggan
- Keranjang direset untuk transaksi berikutnya
