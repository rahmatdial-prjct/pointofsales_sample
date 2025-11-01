# Standarisasi Class Diagram & Sequence Diagram (Modul 2 & 3 APSI 2025)

---

## 📘 Modul 2: Class Diagram & Sequence Diagram

### 🔷 Class Diagram

#### Definisi

Diagram yang menggambarkan struktur statis dari sistem melalui representasi kelas, atribut, metode, dan relasi antar kelas dalam paradigma berorientasi objek.

#### Manfaat

* **Komunikasi Tim**: Memfasilitasi diskusi antar developer, analyst, dan stakeholder
* **Dokumentasi Sistem**: Menyediakan blueprint struktur aplikasi
* **Desain Berorientasi Objek**: Membantu dalam perencanaan arsitektur software
* **Maintenance**: Memudahkan pemeliharaan dan pengembangan sistem

#### Komponen Utama

| Komponen      | Deskripsi                                                          | Contoh                    |
| ------------- | ------------------------------------------------------------------ | ------------------------- |
| **Class**     | Tiga bagian: nama, atribut, dan metode                             | User, Product, Transaction |
| **Attribute** | Ditulis dengan visibilitas: + (public), - (private), # (protected) | - name: String, + id: int |
| **Method**    | Operasi atau fungsi dari suatu kelas                               | + login(), - validateData() |

#### Visibility Modifiers

| Simbol | Visibility | Akses                                    |
| ------ | ---------- | ---------------------------------------- |
| **+**  | Public     | Dapat diakses dari mana saja             |
| **-**  | Private    | Hanya dapat diakses dari dalam kelas     |
| **#**  | Protected  | Dapat diakses oleh subclass              |
| **~**  | Package    | Dapat diakses dalam package yang sama    |

#### Hubungan Antar Kelas

| Tipe               | Simbol      | Multiplicity | Deskripsi                                  |
| ------------------ | ----------- | ------------ | ------------------------------------------ |
| **Association**    | ———————     | 1..*, 0..1   | Hubungan umum antar kelas                  |
| **Aggregation**    | ———————◇    | 1..*, 0..1   | Hubungan bagian dari, tapi independen      |
| **Composition**    | ———————◆    | 1..*, 1      | Hubungan bagian dari yang tidak independen |
| **Generalization** | ———————△    | -            | Pewarisan dari superclass ke subclass      |
| **Dependency**     | - - - - ->  | -            | Ketergantungan sementara                   |
| **Realization**    | - - - - -△  | -            | Implementasi interface                     |

#### Contoh Class Diagram untuk POS System

```
+-------------------+         +-------------------+
|      User         |         |     Branch        |
+-------------------+         +-------------------+
| - id: int         |1       1| - id: int         |
| - name: String    |◆————————| - name: String    |
| - email: String   |         | - address: String |
| - role: String    |         | - phone: String   |
+-------------------+         +-------------------+
| + login()         |         | + getUsers()      |
| + logout()        |         | + addUser()       |
+-------------------+         +-------------------+
         △                             |
         |                             |1
         |                             |
+-------------------+         +-------------------+
|     Manager       |         |    Transaction    |
+-------------------+         +-------------------+
| - branch_id: int  |         | - id: int         |
+-------------------+    1   *| - total: decimal  |
| + manageProducts()|◆————————| - status: String  |
| + viewReports()   |         | - created_at: Date|
+-------------------+         +-------------------+
                              | + calculate()     |
                              | + complete()      |
                              +-------------------+
```

---

### 🔷 Sequence Diagram

#### Definisi

Diagram yang memperlihatkan interaksi objek dalam sistem berdasarkan urutan waktu, menunjukkan bagaimana pesan dikirim antar objek untuk menyelesaikan suatu fungsi.

#### Notasi dan Elemen

| Elemen                     | Simbol           | Fungsi                                            |
| -------------------------- | ---------------- | ------------------------------------------------- |
| **Actor**                  | Stickman         | Pengguna atau sistem eksternal                    |
| **Object**                 | Kotak            | Entitas dalam sistem                              |
| **Lifeline**               | Garis vertikal   | Garis vertikal menunjukkan kehidupan objek        |
| **Execution Occurrence**   | Persegi panjang  | Persegi panjang di lifeline untuk aktivitas objek |
| **Synchronous Message**    | →               | Panah solid untuk pesan sinkron                   |
| **Asynchronous Message**   | ⇢               | Panah terbuka untuk pesan asinkron                |
| **Return Message**         | ← - - -         | Panah putus-putus untuk balasan                   |
| **Self Message**           | ↻               | Pesan yang dikirim ke objek itu sendiri           |
| **Guard Condition**        | [condition]      | Syarat pengiriman pesan                           |
| **Frame (alt/loop/opt)**   | Kotak besar      | Blok logika bercabang atau perulangan             |
| **Object Creation**        | <<create>>       | Pembuatan objek baru                              |
| **Object Destruction**     | X                | Simbol X untuk akhir lifeline objek               |

#### Jenis Frame Logika

| Frame Type | Deskripsi                           | Contoh Penggunaan        |
| ---------- | ----------------------------------- | ------------------------ |
| **alt**    | Alternative (if-else)               | Validasi login           |
| **opt**    | Optional (if tanpa else)            | Logging opsional         |
| **loop**   | Perulangan                          | Memproses multiple items |
| **par**    | Parallel execution                  | Concurrent processing    |
| **break**  | Break dari loop                     | Error handling           |
| **ref**    | Reference ke sequence diagram lain  | Modular design           |

#### Contoh Sequence Diagram untuk Login POS System

```
Aktor: Manajer
Objek: :LoginPage, :AuthController, :UserService, :Database

Manajer -> :LoginPage: inputCredentials(email, password)
:LoginPage -> :AuthController: authenticate(email, password)
:AuthController -> :UserService: validateUser(email, password)
:UserService -> :Database: findUserByEmail(email)
:Database --> :UserService: userData
alt [user exists AND password valid]
    :UserService --> :AuthController: authSuccess(userData)
    :AuthController -> :LoginPage: redirectToDashboard()
    :LoginPage --> Manajer: showDashboard()
else [invalid credentials]
    :UserService --> :AuthController: authFailed()
    :AuthController -> :LoginPage: showErrorMessage()
    :LoginPage --> Manajer: displayError("Invalid credentials")
end
```

#### Pola ECB (Entity-Control-Boundary)

| Komponen       | Deskripsi                        | Contoh dalam POS         |
| -------------- | -------------------------------- | ------------------------ |
| **Entity**     | Menyimpan data dan logika bisnis | User, Product, Transaction |
| **Control**    | Mengatur proses dan alur         | AuthController, TransactionController |
| **Boundary**   | Antarmuka pengguna               | LoginPage, DashboardPage |

#### Pedoman Pembuatan Sequence Diagram

1. **Identifikasi Aktor dan Objek**: Tentukan siapa yang terlibat dalam skenario
2. **Gunakan Notasi Standar**: Konsisten dengan standar UML
3. **Penamaan Pesan Jelas**: Gunakan verb yang menjelaskan aksi
4. **Urutan Waktu**: Dari atas ke bawah, kiri ke kanan
5. **Guard Conditions**: Gunakan untuk logika percabangan
6. **Return Messages**: Tampilkan balasan penting saja

---

## 📗 Modul 3: Deployment Diagram & Component Diagram

### 🔶 Deployment Diagram

#### Definisi

Menunjukkan hubungan fisik antara hardware dan software dalam sistem, termasuk bagaimana komponen software didistribusikan ke node hardware.

#### Notasi

| Komponen        | Simbol          | Fungsi                             |
| --------------- | --------------- | ---------------------------------- |
| **Node**        | Kubus 3D        | Entitas fisik (hardware/software)  |
| **Artifact**    | Dokumen         | File executable, library, database |
| **Component**   | Kotak komponen  | Aplikasi atau server               |
| **Association** | Garis           | Jalur komunikasi antar elemen      |
| **Dependency**  | Panah putus     | Ketergantungan antar komponen      |

#### Contoh Deployment Diagram untuk POS System

```
+------------------------+         +------------------------+
|    Client Machine      |         |    Application Server |
|    [Node]              |         |    [Node]              |
|------------------------|         |------------------------|
| - Web Browser          |  HTTPS  | - Laravel App          |
| - POS Interface        |<------->| - PHP Runtime          |
|   [Component]          |         | - Nginx Web Server     |
+------------------------+         +------------------------+
                                            |
                                            | TCP/IP
                                            v
                                   +------------------------+
                                   |    Database Server     |
                                   |    [Node]              |
                                   |------------------------|
                                   | - MySQL Database       |
                                   | - pos_system.db        |
                                   |   [Artifact]           |
                                   +------------------------+
```

---

### 🔶 Component Diagram

#### Definisi

Menampilkan bagaimana komponen perangkat lunak saling berinteraksi dan disusun dalam sistem, fokus pada struktur internal aplikasi.

#### Stereotype Umum untuk Laravel/POS System

| Stereotype         | Fungsi                            | Contoh dalam POS         |
| ------------------ | --------------------------------- | ------------------------ |
| **<<controller>>** | Pengendali alur kerja             | UserController, ProductController |
| **<<service>>**    | Komponen logika bisnis            | AuthService, ReportService |
| **<<repository>>** | Komponen data (database)          | UserRepository, ProductRepository |
| **<<model>>**      | Representasi data                 | User, Product, Transaction |
| **<<middleware>>** | Filter request/response           | AuthMiddleware, RoleMiddleware |
| **<<interface>>**  | Kontrak komunikasi antar komponen | UserInterface, PaymentInterface |
| **<<facade>>**     | Simplified interface              | DB, Auth, Cache |

#### Notasi

| Elemen          | Simbol          | Deskripsi                         |
| --------------- | --------------- | --------------------------------- |
| **Component**   | Kotak komponen  | Modul perangkat lunak             |
| **Package**     | Folder          | Pengelompokan class/komponen      |
| **Interface**   | Lingkaran       | Lingkaran kecil, untuk komunikasi |
| **Port**        | Kotak kecil     | Titik komunikasi antar komponen   |
| **Provided**    | Lingkaran       | Interface yang disediakan         |
| **Required**    | Setengah lingkaran | Interface yang dibutuhkan      |

#### Contoh Component Diagram untuk POS System

```
+----------------------------------+
|         POS System Package       |
|                                  |
| +------------------------------+ |
| | <<controller>>               | |
| | AuthController               | |
| |------------------------------| |
| | + login()                    | |
| | + logout()                   | |
| +------------------------------+ |
|              |                   |
|              | uses              |
|              v                   |
| +------------------------------+ |
| | <<service>>                  | |
| | AuthService                  | |
| |------------------------------| |
| | + authenticate()             | |
| | + generateToken()            | |
| +------------------------------+ |
|              |                   |
|              | uses              |
|              v                   |
| +------------------------------+ |
| | <<repository>>               | |
| | UserRepository               | |
| |------------------------------| |
| | + findByEmail()              | |
| | + create()                   | |
| +------------------------------+ |
+----------------------------------+
```

---

## 📚 Best Practices untuk POS System

### Class Diagram
- **Konsistensi Penamaan**: Gunakan PascalCase untuk class, camelCase untuk method
- **Single Responsibility**: Setiap class memiliki tanggung jawab yang jelas
- **Encapsulation**: Gunakan visibility modifier yang tepat
- **Inheritance**: Gunakan untuk menghindari duplikasi kode

### Sequence Diagram
- **Skenario Spesifik**: Fokus pada satu use case per diagram
- **Error Handling**: Sertakan skenario error dalam alt frame
- **Performance**: Pertimbangkan asynchronous message untuk operasi berat
- **Security**: Tampilkan validasi dan authorization steps

### Deployment Diagram
- **Scalability**: Pertimbangkan load balancer dan multiple servers
- **Security**: Tampilkan firewall dan security layers
- **Backup**: Sertakan backup servers dan disaster recovery

### Component Diagram
- **Modularity**: Pisahkan concerns dengan jelas
- **Dependency**: Minimalisir coupling antar komponen
- **Interface**: Gunakan interface untuk loose coupling
- **Testability**: Desain untuk memudahkan unit testing

---

## 🎯 Implementasi dalam Laravel POS System

### Mapping ke Struktur Laravel
- **Controller** → app/Http/Controllers/
- **Service** → app/Services/
- **Repository** → app/Repositories/
- **Model** → app/Models/
- **Middleware** → app/Http/Middleware/

### Contoh Implementasi
```php
// AuthController (Boundary)
class AuthController extends Controller {
    public function login(AuthService $authService) { }
}

// AuthService (Control)
class AuthService {
    public function authenticate(UserRepository $userRepo) { }
}

// UserRepository (Entity)
class UserRepository {
    public function findByEmail($email) { }
}
```

---

*Dokumen ini dibuat sebagai standar untuk pembuatan Class Diagram dan Sequence Diagram dalam project POS System UMKM, mengikuti best practices dan standar UML.*
