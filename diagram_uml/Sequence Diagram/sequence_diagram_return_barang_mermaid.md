# Sequence Diagram - Proses Return Barang

```mermaid
sequenceDiagram
    participant P as 👤 Pegawai
    participant M as 👨‍💼 Manajer
    participant S as 💻 Sistem POS
    participant D as 🗄️ Database

    Note over P,D: Proses Return Barang

    P->>S: 1. Terima Permintaan Return dari Pelanggan
    S->>P: 2. Tampilkan Form Return
    
    P->>S: 3. Input Nomor Invoice
    S->>D: 4. Query Transaksi Asli
    
    alt Transaksi Ditemukan
        D->>S: 5. Return Data Transaksi
        S->>S: 6. Validasi Periode Return
        
        alt Masih dalam Periode
            S->>P: 7. Tampilkan Detail Transaksi
            
            P->>S: 8. Pilih Produk & Input Jumlah Return
            P->>S: 9. Input Alasan Return
            S->>S: 10. Validasi Kondisi Return
            
            alt Kondisi Valid
                S->>S: 11. Hitung Nilai Return
                S->>S: 12. Generate Nomor Return
                S->>S: 13. Cek Role Pegawai
                
                alt Role = Manajer
                    S->>D: 14. Simpan Return (Status: Approved)
                    D->>S: 15. Return ID
                    
                    S->>D: 16. Update Stok Produk
                    D->>S: 17. Konfirmasi Update Stok
                    
                    S->>S: 18. Generate Nota Return
                    S->>P: 19. Tampilkan Nota Return
                    P->>S: 20. Cetak Nota Return
                    S->>P: 21. Return Berhasil Diproses
                    
                else Role = Pegawai Biasa
                    S->>D: 22. Simpan Return (Status: Pending)
                    D->>S: 23. Return ID
                    
                    S->>M: 24. Kirim Notifikasi Approval
                    S->>P: 25. Informasikan Menunggu Approval
                    
                    Note over M,S: Approval Process
                    M->>S: 26. Review Permintaan Return
                    S->>D: 27. Query Detail Return
                    D->>S: 28. Return Data Return
                    S->>M: 29. Tampilkan Detail Return
                    
                    M->>S: 30. Keputusan Approval
                    
                    alt Manajer Approve
                        S->>D: 31. Update Status Return (Approved)
                        D->>S: 32. Konfirmasi Update
                        
                        S->>D: 33. Update Stok Produk
                        D->>S: 34. Konfirmasi Update Stok
                        
                        S->>S: 35. Generate Nota Return
                        S->>P: 36. Notifikasi Approval
                        S->>M: 37. Konfirmasi Approval Tersimpan
                        
                    else Manajer Reject
                        S->>D: 38. Update Status Return (Rejected)
                        D->>S: 39. Konfirmasi Update
                        
                        S->>P: 40. Notifikasi Penolakan
                        S->>M: 41. Konfirmasi Penolakan Tersimpan
                    end
                end
                
            else Kondisi Tidak Valid
                S->>P: 42. Error: Kondisi Return Tidak Memenuhi Syarat
            end
            
        else Periode Habis
            S->>P: 43. Error: Periode Return Sudah Habis
        end
        
    else Transaksi Tidak Ditemukan
        D->>S: 44. Transaksi Not Found
        S->>P: 45. Error: Transaksi Tidak Ditemukan
    end

    Note over P,D: Return Berhasil: Data tersimpan, Stok dikembalikan, Nota tercetak
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi antar komponen dalam proses return barang dengan workflow approval berdasarkan role pengguna.

### 👥 **Participants**
- **👤 Pegawai**: Staff yang menerima dan memproses return dari pelanggan
- **👨‍💼 Manajer**: Supervisor yang melakukan approval untuk return
- **💻 Sistem POS**: Aplikasi yang memproses logic return dan approval
- **🗄️ Database**: Penyimpanan data transaksi, return, dan stok

### 🔄 **Alur Interaksi**
1. **Inisialisasi**: Pegawai terima permintaan → System tampilkan form return
2. **Validation**: Input invoice → Query transaksi asli → Validasi periode
3. **Return Input**: Pilih produk → Input jumlah & alasan → Validasi kondisi
4. **Role Check**: System cek role pegawai untuk menentukan approval flow
5. **Approval Flow**: Manajer langsung approve atau pegawai butuh approval
6. **Finalization**: Update stok → Generate nota → Notifikasi hasil

### ⚠️ **Alternative Flows**
- **Transaksi Tidak Ditemukan**: Invoice tidak ada di database
- **Periode Habis**: Return dilakukan setelah batas waktu
- **Kondisi Tidak Valid**: Barang tidak memenuhi syarat return
- **Role-based Flow**: Manajer vs Pegawai biasa
- **Approval Decision**: Approve vs Reject oleh manajer

### 🔐 **Role-based Workflow**
- **Manajer**: Dapat approve return langsung tanpa menunggu
- **Pegawai Biasa**: Harus menunggu approval dari manajer
- **Approval Process**: Notifikasi → Review → Decision → Update status

### 💾 **Database Interactions**
- **Query Transaksi**: Validasi invoice dan data transaksi asli
- **Simpan Return**: Save return data dengan status (Pending/Approved)
- **Update Status**: Change status berdasarkan approval decision
- **Update Stok**: Kembalikan stok jika return approved

### 📊 **Output**
- Data return tersimpan dengan status yang tepat
- Stok produk dikembalikan ke inventory (jika approved)
- Nota return untuk pelanggan
- Audit trail lengkap untuk approval process
