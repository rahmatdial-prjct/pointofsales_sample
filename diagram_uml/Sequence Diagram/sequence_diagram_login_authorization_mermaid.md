# Sequence Diagram - Proses Login & Authorization

```mermaid
sequenceDiagram
    participant U as 👤 User
    participant S as 💻 Sistem POS
    participant D as 🗄️ Database

    Note over U,D: Proses Login & Authorization

    U->>S: 1. Buka Aplikasi POS
    S->>U: 2. Tampilkan Halaman Login
    
    U->>S: 3. Input Kredensial (Email/Password)
    S->>S: 4. Validasi Format Input
    
    alt Format Valid
        S->>D: 5. Query User Data
        
        alt User Ditemukan & Aktif
            D->>S: 6. Return User Data
            S->>S: 7. Verifikasi Password
            
            alt Password Benar
                S->>D: 8. Generate & Simpan Session
                D->>S: 9. Session Token
                S->>S: 10. Identifikasi Role User
                
                alt Role = Direktur
                    S->>S: 11. Set Permission Direktur
                    Note right of S: Full access semua cabang
                    S->>U: 12. Redirect Dashboard Direktur
                    
                else Role = Manajer
                    S->>S: 13. Set Permission Manajer
                    Note right of S: Access cabang sendiri
                    S->>U: 14. Redirect Dashboard Manajer
                    
                else Role = Pegawai
                    S->>S: 15. Set Permission Pegawai
                    Note right of S: Limited access
                    S->>U: 16. Redirect Dashboard Pegawai
                end
                
                S->>D: 17. Update Last Login & Log Aktivitas
                D->>S: 18. Confirmation
                
            else Password Salah
                S->>S: 19. Increment Login Attempt
                
                alt Attempt > 3
                    S->>D: 20. Lock Account & Log Security
                    D->>S: 21. Confirmation
                    S->>U: 22. Account Locked Message
                    
                else Attempt <= 3
                    S->>U: 23. Password Salah Message
                end
            end
            
        else User Tidak Ditemukan/Tidak Aktif
            D->>S: 24. User Not Found/Inactive
            S->>U: 25. Error Message
        end
        
    else Format Invalid
        S->>U: 26. Format Invalid Message
    end

    Note over U,D: Login Berhasil: Session aktif, Permission set, Dashboard loaded
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi antar komponen sistem dalam proses login dan authorization dengan urutan waktu yang jelas.

### 👥 **Participants**
- **👤 User**: Pengguna sistem (Direktur/Manajer/Pegawai)
- **💻 Sistem POS**: Aplikasi POS yang memproses logic
- **🗄️ Database**: Penyimpanan data user dan session

### 🔄 **Alur Interaksi**
1. **Inisialisasi**: User buka aplikasi → System tampilkan login form
2. **Input Validation**: User input kredensial → System validasi format
3. **Authentication**: System query database → Verifikasi user & password
4. **Authorization**: System tentukan role → Set permission sesuai role
5. **Session Management**: Generate session token → Update last login
6. **Redirect**: User diarahkan ke dashboard sesuai role

### ⚠️ **Alternative Flows**
- **Format Invalid**: Validasi input gagal
- **User Not Found**: User tidak ada di database
- **Password Salah**: Password tidak match (dengan attempt counter)
- **Account Locked**: Terlalu banyak failed attempts
- **Role-based Redirect**: Dashboard berbeda per role

### 🔐 **Security Features**
- **Input Validation**: Format checking sebelum database query
- **Password Verification**: Secure password checking
- **Session Management**: Token-based session dengan database storage
- **Account Locking**: Protection dari brute force attacks
- **Audit Logging**: Pencatatan aktivitas login untuk security

### 📊 **Output**
- Session aktif dengan token yang valid
- Permission set sesuai dengan role user
- Dashboard loaded sesuai dengan authorization level
- Log aktivitas tercatat untuk audit trail
