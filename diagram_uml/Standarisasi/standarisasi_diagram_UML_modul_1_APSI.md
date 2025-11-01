# Standarisasi Diagram UML (Modul 1 APSI 2025)

## 📌 Use Case Diagram

### Definisi

Use Case Diagram adalah representasi visual dari interaksi pengguna (aktor) dengan sistem untuk menunjukkan fungsi-fungsi utama yang disediakan oleh sistem. Diagram ini berfokus pada **apa** yang dapat dilakukan sistem dari perspektif pengguna eksternal.

### Manfaat

* **Identifikasi Aktor**: Mengidentifikasi semua stakeholder dan perannya dalam sistem
* **Functional Requirements**: Menggambarkan hasil dari analisis kebutuhan fungsional
* **Komunikasi Tim**: Memfasilitasi diskusi antara developer, analyst, dan client
* **Scope Definition**: Menunjukkan batasan dan cakupan sistem
* **Pandangan Eksternal**: Memberikan perspektif black-box dari sistem
* **Basis Testing**: Menjadi dasar untuk test case dan user acceptance testing

### Karakteristik

* **Actor-Driven**: Setiap use case dipicu oleh aktor (internal atau eksternal)
* **Value-Oriented**: Memberikan hasil yang bernilai kepada aktor
* **Goal-Focused**: Menampilkan tujuan yang ingin dicapai aktor
* **System Boundary**: Jelas membedakan apa yang ada di dalam dan luar sistem
* **High-Level**: Menggambarkan fungsi pada level tinggi, bukan detail implementasi

### Notasi dan Elemen

| Notasi              | Simbol         | Fungsi                                     | Contoh Penggunaan        |
| ------------------- | -------------- | ------------------------------------------ | ------------------------ |
| **Use Case**        | Oval           | Mewakili aktivitas atau layanan utama      | Login, Kelola Produk     |
| **Actor**           | Stickman       | Mewakili pengguna atau sistem eksternal    | Direktur, Manajer, Pegawai |
| **Association**     | Garis solid    | Hubungan antara aktor dan use case         | Manajer --- Kelola Produk |
| **Generalization**  | Panah segitiga | Relasi pewarisan antar aktor atau use case | User ←△— Manajer         |
| **System Boundary** | Kotak besar    | Batas sistem untuk menunjukkan konteks     | POS System Boundary      |
| **<<include>>**     | Panah + label  | Use case wajib yang selalu dipanggil       | Login <<include>> Validasi |
| **<<extend>>**      | Panah + label  | Use case tambahan yang bersyarat           | Checkout <<extend>> Diskon |

### Jenis Aktor

| Jenis Aktor        | Deskripsi                           | Contoh dalam POS System  |
| ------------------ | ----------------------------------- | ------------------------ |
| **Primary Actor**  | Aktor utama yang memulai use case   | Direktur, Manajer, Pegawai |
| **Secondary Actor** | Aktor yang memberikan layanan       | Payment Gateway, Printer |
| **Offstage Actor** | Aktor yang berkepentingan tapi tidak berinteraksi langsung | Auditor, Regulator |

### Use Case Description

Dokumen teks yang merinci bagaimana sistem digunakan dari sudut pandang aktor, memberikan detail implementasi untuk setiap use case.

**Elemen Wajib:**

| Elemen                    | Deskripsi                                    | Contoh                           |
| ------------------------- | -------------------------------------------- | -------------------------------- |
| **Name**                  | Nama use case yang jelas dan deskriptif     | "Kelola Produk"                  |
| **Description**           | Penjelasan singkat tujuan use case          | "Manajer dapat menambah, edit, hapus produk" |
| **Actor**                 | Siapa yang terlibat dalam use case          | "Manajer"                        |
| **Precondition**          | Kondisi yang harus dipenuhi sebelum eksekusi | "Manajer sudah login"            |
| **Postcondition**         | Kondisi setelah use case berhasil           | "Produk tersimpan di database"   |
| **Trigger**               | Apa yang memulai use case                   | "Manajer klik tombol Tambah Produk" |
| **Main Flow**             | Alur utama langkah demi langkah              | "1. Input data, 2. Validasi, 3. Simpan" |
| **Alternative Flow**      | Alur alternatif jika ada pilihan lain       | "Jika edit: 1. Pilih produk, 2. Edit, 3. Update" |
| **Exception Flow**        | Alur jika terjadi error                     | "Jika validasi gagal: tampilkan pesan error" |

**Template Use Case Description:**
```
Use Case: [Nama Use Case]
Actor: [Primary Actor]
Description: [Deskripsi singkat]
Precondition: [Kondisi awal]
Trigger: [Pemicu]

Main Flow:
1. [Langkah 1]
2. [Langkah 2]
3. [dst...]

Alternative Flow:
A1. [Kondisi alternatif]
  A1.1. [Langkah alternatif]
  A1.2. [Kembali ke langkah X]

Exception Flow:
E1. [Kondisi error]
  E1.1. [Penanganan error]
  E1.2. [Use case berakhir]

Postcondition: [Kondisi akhir]
```

---

## 🔁 Activity Diagram

### Definisi

Activity Diagram adalah diagram yang menggambarkan alur kerja atau proses dalam sistem secara visual dan dinamis, menunjukkan **bagaimana** aktivitas dilakukan dari awal hingga akhir.

### Manfaat

* **Process Modeling**: Menunjukkan urutan aktivitas dalam proses bisnis
* **Use Case Detailing**: Dapat mendokumentasikan detail proses tiap use case
* **Parallel Processing**: Mengidentifikasi aktivitas yang dapat dilakukan bersamaan
* **Decision Points**: Menunjukkan titik-titik pengambilan keputusan
* **Workflow Optimization**: Membantu mengidentifikasi bottleneck dan inefficiency
* **Business Process**: Dokumentasi proses bisnis yang dapat dipahami non-teknis

### Karakteristik

* **Control Flow**: Menggambarkan alur kontrol dari satu aktivitas ke aktivitas lain
* **Object Flow**: Menunjukkan perpindahan objek/data antar aktivitas
* **Concurrency**: Mendukung percabangan dan paralelisme
* **Decision Logic**: Dapat menampilkan logika percabangan yang kompleks
* **Swimlane Support**: Dapat menunjukkan siapa yang bertanggung jawab
* **UML Compliant**: Menggunakan notasi standar UML 2.0

### Notasi dan Elemen

| Notasi            | Simbol                 | Fungsi                                | Contoh Penggunaan        |
| ----------------- | ---------------------- | ------------------------------------- | ------------------------ |
| **Initial Node**  | ● (titik hitam)        | Awal proses                           | Start login process      |
| **Final Node**    | ◉ (lingkaran titik)    | Akhir proses                          | End transaction          |
| **Action**        | [Kotak rounded]        | Aktivitas dasar yang tidak dapat dibagi | "Validasi Input"       |
| **Activity**      | [Kotak lebar]          | Serangkaian aksi kompleks             | "Proses Pembayaran"      |
| **Object Node**   | [Kotak objek]          | Representasi objek/data yang terlibat | "Data Produk"            |
| **Control Flow**  | →                      | Alur kontrol antar aktivitas          | Activity A → Activity B  |
| **Object Flow**   | → (dengan objek)       | Alur objek/data antar aktivitas       | Data → Process           |
| **Decision Node** | ◇ (diamond)            | Percabangan berdasarkan kondisi       | [Valid?]                 |
| **Merge Node**    | ◇ (diamond)            | Penggabungan alur tanpa kondisi       | Merge paths              |
| **Fork Node**     | ▬ (garis tebal)        | Memisahkan aktivitas paralel          | Parallel processing      |
| **Join Node**     | ▬ (garis tebal)        | Menggabungkan aktivitas paralel       | Sync parallel tasks      |
| **Swimlane**      | \|\| (kolom/baris)     | Menandai siapa yang bertanggung jawab | Manajer \| Pegawai       |

### Jenis Activity Diagram

| Jenis                    | Fokus                              | Penggunaan                    |
| ------------------------ | ---------------------------------- | ----------------------------- |
| **Business Process**     | Proses bisnis level tinggi         | Workflow perusahaan           |
| **Use Case Activity**    | Detail implementasi use case       | Spesifikasi fungsional        |
| **Method Activity**      | Algoritma dalam method             | Detail implementasi code      |
| **Concurrent Activity**  | Proses paralel dan sinkronisasi    | Multi-threading, distributed  |

---

### Contoh Activity Diagram untuk POS System

```
● Start
↓
[Login ke Sistem]
↓
◇ [Kredensial Valid?]
├─ No → [Tampilkan Error] → ◉ End
└─ Yes ↓
[Pilih Menu]
↓
◇ [Menu yang dipilih?]
├─ Kelola Produk → [CRUD Produk] ↓
├─ Transaksi → [Proses Transaksi] ↓
└─ Laporan → [Generate Laporan] ↓
↓
[Logout]
↓
◉ End
```

### Swimlane Example untuk Transaksi POS

```
|    Pegawai    |    Sistem     |    Database   |
|---------------|---------------|---------------|
| ● Start       |               |               |
| [Scan Produk] |               |               |
|       ↓       |               |               |
|               | [Cari Produk] |               |
|               |       ↓       |               |
|               |               | [Query Produk]|
|               |               |       ↓       |
|               | [Update Total]|               |
|               |       ↓       |               |
| [Konfirmasi]  |               |               |
|       ↓       |               |               |
|               | [Simpan]      |               |
|               |       ↓       |               |
|               |               | [Insert Trans]|
| ◉ End         |               |               |
```

---

## 📋 Functional Requirement

### Definisi

Functional Requirement merupakan pernyataan tentang fungsi apa yang harus disediakan sistem, menjelaskan **apa** yang dapat dilakukan sistem tanpa menjelaskan **bagaimana** implementasinya.

### Karakteristik

* **Specific**: Jelas dan tidak ambigu
* **Measurable**: Dapat diukur dan diverifikasi
* **Achievable**: Dapat diimplementasikan dengan teknologi yang ada
* **Relevant**: Sesuai dengan kebutuhan bisnis
* **Traceable**: Dapat dilacak dari requirement ke implementasi

### Format Dokumentasi

Biasanya dituliskan dalam tabel yang mencakup:

| Elemen              | Deskripsi                           | Contoh                    |
| ------------------- | ----------------------------------- | ------------------------- |
| **ID**              | Identifier unik requirement         | FR-001, FR-002            |
| **Aktor**           | Siapa yang melakukan fungsi         | Direktur, Manajer, Pegawai |
| **Deskripsi Peran** | Peran aktor dalam sistem            | "Mengelola seluruh cabang" |
| **Fungsi**          | Fungsi-fungsi yang dapat dilakukan | Login, CRUD Produk, Laporan |
| **Priority**        | Tingkat kepentingan (1-5)           | 5 (Critical), 3 (Medium)  |
| **Status**          | Status implementasi                 | Implemented, Pending      |

### Template Functional Requirement untuk POS System

```
| ID    | Aktor    | Fungsi                    | Deskripsi                           | Priority |
|-------|----------|---------------------------|-------------------------------------|----------|
| FR-001| Direktur | Kelola Cabang             | Dapat CRUD data cabang              | 5        |
| FR-002| Direktur | Lihat Laporan Terintegrasi| Dapat melihat laporan semua cabang  | 4        |
| FR-003| Manajer  | Kelola Produk             | Dapat CRUD produk di cabangnya      | 5        |
| FR-004| Manajer  | Kelola Pegawai            | Dapat CRUD data pegawai cabang      | 4        |
| FR-005| Pegawai  | Proses Transaksi          | Dapat melakukan transaksi penjualan | 5        |
| FR-006| Pegawai  | Kelola Return             | Dapat memproses return produk       | 3        |
```

---

---

## 📝 Catatan Implementasi

### Konsistensi Penamaan
- **Bahasa**: Gunakan bahasa Indonesia untuk nama aktor: **Direktur**, **Manajer**, **Pegawai**
- **Terminologi**: Pastikan konsistensi antara diagram UML dan implementasi UI
- **Dokumentasi**: Gunakan terminologi yang sama di seluruh dokumentasi
- **Use Case Naming**: Gunakan format "Verb + Object" (contoh: "Kelola Produk", "Proses Transaksi")

### Best Practices Use Case Diagram
- **Actor Identification**: Setiap aktor harus merepresentasikan peran yang nyata dalam sistem
- **Use Case Granularity**: Tidak terlalu detail (bukan step-by-step) dan tidak terlalu umum
- **System Boundary**: Jelas membedakan apa yang ada di dalam dan luar sistem
- **Relationship Usage**: Gunakan include/extend hanya jika benar-benar diperlukan
- **Documentation**: Setiap use case harus memiliki deskripsi yang jelas

### Best Practices Activity Diagram
- **Swimlane Usage**: Gunakan swimlane untuk menunjukkan tanggung jawab yang jelas
- **Decision Points**: Pastikan setiap decision node memiliki kondisi yang jelas
- **Parallel Activities**: Gunakan fork/join untuk aktivitas yang benar-benar paralel
- **Object Flow**: Tampilkan object flow jika perpindahan data penting
- **Error Handling**: Sertakan path untuk error handling

### Tools yang Direkomendasikan

| Tool        | Kelebihan                           | Penggunaan Terbaik        |
| ----------- | ----------------------------------- | ------------------------- |
| **Mermaid** | Integrasi dengan dokumentasi, Git  | Diagram sederhana, CI/CD  |
| **PlantUML**| Text-based, version control friendly | Diagram kompleks, automation |
| **Draw.io** | Visual editing, gratis, collaborative | Prototyping, presentasi   |
| **Visual Paradigm** | Professional, lengkap, code generation | Enterprise, full SDLC |
| **Lucidchart** | Collaborative, cloud-based       | Team collaboration        |

### Mapping ke Implementasi Laravel

| UML Element    | Laravel Implementation              | Contoh                    |
| -------------- | ----------------------------------- | ------------------------- |
| **Actor**      | Role/Permission                     | 'director', 'manager'     |
| **Use Case**   | Controller Method                   | ProductController@store   |
| **System**     | Laravel Application                 | POS System                |
| **Boundary**   | Routes, Middleware                  | web.php, auth middleware  |

### Quality Checklist

**Use Case Diagram:**
- [ ] Semua aktor teridentifikasi
- [ ] Use case memberikan value kepada aktor
- [ ] System boundary jelas
- [ ] Relationship tepat dan minimal
- [ ] Naming convention konsisten

**Activity Diagram:**
- [ ] Initial dan final node ada
- [ ] Semua path dapat dilalui
- [ ] Decision node memiliki kondisi jelas
- [ ] Swimlane assignment tepat
- [ ] Parallel activities benar-benar independen

**Functional Requirements:**
- [ ] Setiap requirement memiliki ID unik
- [ ] Deskripsi jelas dan tidak ambigu
- [ ] Priority level ditetapkan
- [ ] Traceability ke use case ada
- [ ] Acceptance criteria didefinisikan

---

## 🎯 Contoh Implementasi untuk POS System

### Use Case Diagram POS System
```
                    ┌─────────────────────────────────────┐
                    │           POS System                │
                    │                                     │
Direktur ────────── │  ○ Kelola Cabang                    │
   │                │  ○ Kelola User                      │
   │                │  ○ Lihat Laporan Terintegrasi       │
   │                │                                     │
   └─────────────── │  ○ Login ←──────────────────────────│ ──── Manajer
                    │    │                                │      │
                    │    │ <<include>>                    │      │
                    │    ↓                                │      │
                    │  ○ Validasi Kredensial              │      │
                    │                                     │      │
                    │  ○ Kelola Produk ───────────────────│ ─────┘
                    │  ○ Kelola Pegawai                   │
                    │  ○ Kelola Return                    │      Pegawai
                    │                                     │      │
                    │  ○ Proses Transaksi ────────────────│ ─────┘
                    │  ○ Cetak Struk                      │
                    └─────────────────────────────────────┘
```

### Activity Diagram: Proses Transaksi
```
Pegawai          │    Sistem           │    Database
                 │                     │
● Start          │                     │
│                │                     │
[Scan Produk]    │                     │
│                │                     │
─────────────────→ [Cari Produk]       │
                 │         │           │
                 │         └──────────→ [Query Produk]
                 │                     │         │
                 │ [Update Keranjang] ←┘         │
                 │         │           │         │
[Tambah Produk?] ←─────────┘           │         │
│                │                     │         │
◇ [Selesai?]     │                     │         │
├─No─┐           │                     │         │
│    └───────────→ [Lanjut Scan]       │         │
│                │         │           │         │
└─Yes            │         │           │         │
│                │         │           │         │
[Input Pembayaran]│        │           │         │
│                │         │           │         │
─────────────────→ [Hitung Total]      │         │
                 │         │           │         │
                 │ [Proses Bayar] ─────┼────────→ [Simpan Transaksi]
                 │         │           │         │
                 │ [Cetak Struk] ←─────┼─────────┘
                 │         │           │
[Terima Struk] ←─┘         │           │
│                │         │           │
◉ End            │         │           │
```

---

*Dokumen ini dibuat sebagai standar untuk pembuatan diagram UML dalam project POS System UMKM, mengikuti best practices dan standar UML 2.0.*
