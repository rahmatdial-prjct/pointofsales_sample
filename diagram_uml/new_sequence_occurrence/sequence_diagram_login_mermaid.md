# Sequence Diagram - Login & Authentication (Simplified)

```mermaid
sequenceDiagram
    participant U as 👤 User;
    participant UI as 🖥️ UI;
    participant C as 🌐 Controller;
    participant D as 🗄️ Database;

    Note over U,D: Login & Authentication;

    U->>UI: 1. Akses Halaman Login;
    activate UI
    UI->>C: :order(akses_halaman_login);
    activate C
    C-->>UI: :order(form_login);
    deactivate C
    UI-->>U: :order(tampilkan_form);
    deactivate UI

    U->>UI: 2. Input Kredensial;
    activate UI
    UI->>C: :order(proses_login, email, password);
    activate C
    C->>D: :order(validasi_user);
    activate D
    D-->>C: :order(data_user);
    deactivate D

    alt Kredensial Valid
        C->>D: :order(update_last_login);
        activate D
        D-->>C: :order(login_terupdate);
        deactivate D
        C-->>UI: :order(redirect_dashboard);
        deactivate C
        UI-->>U: :order(tampilkan_dashboard);
        deactivate UI
    else Kredensial Invalid
        C-->>UI: :order(login_error);
        deactivate C
        UI-->>U: :order(tampilkan_error);
        deactivate UI
    end

    Note right of U: Implementasi Laravel:<br/>AuthController<br/>User Model<br/>Auth Middleware<br/>Blade Templates;
```

## Penjelasan Sequence Diagram

### 🎯 **Tujuan**
Menggambarkan interaksi sederhana antar komponen dalam proses login dan authentication dengan format 1 actor + 3 objects.

### 👥 **Participants**
- **👤 User**: Actor yang melakukan login (Direktur/Manajer/Pegawai)
- **🖥️ UI**: Interface pengguna (Blade templates)
- **🌐 Controller**: AuthController Laravel
- **🗄️ Database**: MySQL database

### 🔄 **Alur Proses**
1. **Akses Halaman Login**: User membuka form login
2. **Input Kredensial**: Memasukkan email dan password
3. **Validasi**: Sistem memverifikasi kredensial dan redirect

### 💻 **Implementasi Teknis**
- **Laravel Routes**: GET/POST /login
- **Controller**: AuthController
- **Models**: User
- **Middleware**: Auth middleware untuk proteksi
- **Views**: Blade templates untuk UI
- **Database**: MySQL operations untuk authentication
