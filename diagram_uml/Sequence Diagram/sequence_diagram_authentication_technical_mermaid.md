# Sequence Diagram - User Authentication (Technical Implementation Layer)

```mermaid
sequenceDiagram
    participant Manajer as 👤 Manajer
    participant UI as 🖥️ UI
    participant Controller as 🌐 Controller
    participant Database as 🗄️ Database

    %% Login Process
    Manajer->>+UI: 1. Akses halaman login
    UI-->>-Manajer: 1.1. Tampilkan form login

    Manajer->>+UI: 2. Input email dan password
    UI->>+Controller: 2.1. Kirim data login
    Controller->>+Database: 2.2. Validasi user credentials
    Database-->>-Controller: 2.3. Data user atau error

    alt Kredensial valid
        Controller->>+Database: 2.4. Buat session login
        Database-->>-Controller: 2.5. Session berhasil dibuat
        Controller-->>-UI: 2.6. Redirect ke dashboard manajer
        UI-->>-Manajer: 2.7. Tampilkan dashboard
    else Kredensial tidak valid
        Controller-->>-UI: 2.8. Error message
        UI-->>-Manajer: 2.9. Tampilkan pesan error
    end

    %% Protected Route Access
    Manajer->>+UI: 3. Akses halaman produk
    UI->>+Controller: 3.1. Cek authorization
    Controller->>+Database: 3.2. Validasi session dan role
    Database-->>-Controller: 3.3. Status authorization

    alt Role authorized
        Controller-->>-UI: 3.4. Akses diberikan
        UI-->>-Manajer: 3.5. Tampilkan halaman produk
    else Role tidak authorized
        Controller-->>-UI: 3.6. Access denied
        UI-->>-Manajer: 3.7. Tampilkan error 403
    end

    %% Logout Process
    Manajer->>+UI: 4. Klik logout
    UI->>+Controller: 4.1. Request logout
    Controller->>+Database: 4.2. Hapus session
    Database-->>-Controller: 4.3. Session dihapus
    Controller-->>-UI: 4.4. Redirect ke login
    UI-->>-Manajer: 4.5. Tampilkan halaman login

    Note over Manajer,Database: Implementasi Teknis:<br/>- Laravel Auth untuk autentikasi<br/>- Session database untuk penyimpanan<br/>- Role-based middleware untuk otorisasi<br/>- Password hashing dengan bcrypt
```

## Penjelasan Sequence Diagram - User Authentication (Technical Layer)

### 🎯 **Fokus Teknis**
Diagram ini menggambarkan implementasi teknis Laravel untuk sistem autentikasi dan otorisasi, disederhanakan untuk menunjukkan alur utama dengan struktur yang jelas.

### 🏗️ **Komponen Teknis**
- **👤 Manajer**: User dengan role manajer yang mengakses sistem
- **🖥️ UI**: User Interface (Blade views dan frontend)
- **🌐 Controller**: Laravel Controller yang menangani logic
- **🗄️ Database**: Penyimpanan data users, sessions, dan authorization

### 🔄 **Alur Implementasi Teknis**

#### **Phase 1: Login Process**

**1. Akses Halaman Login**
- Manajer mengakses halaman login
- UI menampilkan form login dengan field email dan password
- Form dilengkapi dengan CSRF token untuk keamanan

**2. Proses Autentikasi**
- Manajer input email dan password
- UI mengirim data ke Controller untuk validasi
- Controller melakukan validasi credentials dengan Database
- Database mengembalikan data user atau error jika tidak valid

**3. Hasil Autentikasi**
- **Jika kredensial valid**: Controller membuat session login, redirect ke dashboard manajer
- **Jika kredensial tidak valid**: Controller mengirim error message, UI menampilkan pesan error

#### **Phase 2: Akses Halaman Terproteksi**

**1. Request Akses**
- Manajer mencoba mengakses halaman produk
- UI mengirim request ke Controller untuk cek authorization
- Controller validasi session dan role dengan Database

**2. Hasil Authorization**
- **Jika role authorized**: Controller memberikan akses, UI menampilkan halaman produk
- **Jika role tidak authorized**: Controller menolak akses, UI menampilkan error 403

#### **Phase 3: Logout Process**

**1. Proses Logout**
- Manajer klik tombol logout
- UI mengirim request logout ke Controller
- Controller menghapus session dari Database
- UI redirect ke halaman login dengan konfirmasi logout berhasil

### 🛠️ **Fitur Keamanan Laravel**

#### **Sistem Autentikasi**
- **Laravel Auth**: Sistem autentikasi terpusat
- **Session Database**: Penyimpanan session di database
- **Password Hashing**: Enkripsi password dengan bcrypt
- **CSRF Protection**: Perlindungan dari serangan CSRF

#### **Sistem Otorisasi**
- **Role-Based Access**: Kontrol akses berdasarkan role user
- **Middleware Protection**: Perlindungan route dengan middleware
- **Session Validation**: Validasi session untuk setiap request

### 🔒 **Implementasi Keamanan**

#### **Validasi Input**
- **Server-Side Validation**: Validasi data di sisi server
- **Email Validation**: Validasi format email yang benar
- **Password Security**: Enkripsi dan validasi password

#### **Keamanan Database**
- **Prepared Statements**: Pencegahan SQL injection
- **Active User Check**: Validasi user aktif
- **Session Management**: Pengelolaan session yang aman

### 📊 **Interaksi Database**

#### **Operasi Utama**
- **User Authentication**: Query validasi user credentials
- **Session Creation**: Pembuatan dan penyimpanan session
- **Role Validation**: Validasi role user untuk authorization
- **Session Cleanup**: Pembersihan session saat logout

### 🚀 **Pertimbangan Performa**
- **Database Indexing**: Optimasi query dengan indexing
- **Session Caching**: Caching session untuk performa
- **Middleware Efficiency**: Urutan middleware yang optimal

### 🔄 **Error Handling**
- **Authentication Errors**: Penanganan error login
- **Authorization Errors**: Response 403 untuk akses ditolak
- **Session Expiry**: Redirect otomatis saat session expired
