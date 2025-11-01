# Penjelasan Relasi Class Diagram Sistem POS UMKM

## Penjelasan Hubungan Antar Tabel

### 🔗 **Hubungan Users ↔ Branches (Pengguna ↔ Cabang)**
**Jenis:** Many-to-One (Banyak ke Satu)

**Penjelasan Sederhana:**
Banyak karyawan bisa bekerja di satu cabang, tetapi setiap karyawan hanya terdaftar di satu cabang saja.

**Contoh Konkret:**
- Cabang Jakarta Pusat memiliki: 1 manajer + 3 kasir = 4 karyawan
- Tapi setiap karyawan hanya bekerja di 1 cabang tertentu
- Ahmad (manajer) hanya di Jakarta Pusat, tidak bisa di Bandung

### 🔗 **Hubungan Categories ↔ Products (Kategori ↔ Produk)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu kategori bisa memiliki banyak produk, tetapi setiap produk hanya masuk ke satu kategori.

**Contoh Konkret:**
- Kategori "Hijab" berisi: 50 jenis hijab berbeda
- Kategori "Gamis" berisi: 30 jenis gamis berbeda
- Tapi "Hijab Segi Empat Polos" hanya masuk kategori "Hijab" saja

### 🔗 **Hubungan Branches ↔ Products (Cabang ↔ Produk)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu cabang bisa menjual banyak produk, tetapi setiap produk terdaftar di satu cabang tertentu.

**Contoh Konkret:**
- Cabang Jakarta menjual: 200 jenis produk
- Cabang Bandung menjual: 150 jenis produk
- Stok "Hijab Hitam" di Jakarta: 25 pcs (terpisah dari stok di Bandung)

### 🔗 **Hubungan Users ↔ Transactions (Pengguna ↔ Transaksi)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu karyawan bisa melayani banyak transaksi, tetapi setiap transaksi hanya dilayani oleh satu karyawan.

**Contoh Konkret:**
- Kasir Fatimah hari ini melayani: 25 transaksi
- Kasir Ahmad hari ini melayani: 18 transaksi
- Tapi transaksi INV-001 hanya dicatat atas nama Fatimah saja

### 🔗 **Hubungan Branches ↔ Transactions (Cabang ↔ Transaksi)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu cabang bisa memiliki banyak transaksi, tetapi setiap transaksi hanya terjadi di satu cabang.

**Contoh Konkret:**
- Cabang Jakarta hari ini: 100 transaksi
- Cabang Bandung hari ini: 75 transaksi
- Transaksi Bu Aminah hanya tercatat di cabang Jakarta

### 🔗 **Hubungan Transactions ↔ Transaction Items (Transaksi ↔ Detail)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu nota belanja bisa berisi banyak barang, tetapi setiap barang dalam daftar hanya milik satu nota.

**Contoh Konkret:**
- Nota INV-001 berisi: 3 jenis barang berbeda
- Nota INV-002 berisi: 5 jenis barang berbeda
- Tapi "2x Hijab Hitam" dalam nota INV-001 tidak bisa pindah ke nota lain

### � **Hubungan Products ↔ Transaction Items (Produk ↔ Detail Transaksi)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu produk bisa dijual berkali-kali dalam transaksi berbeda, tetapi setiap item dalam nota merujuk ke satu produk tertentu.

**Contoh Konkret:**
- Hijab Hitam dijual dalam 10 transaksi berbeda hari ini
- Tapi dalam nota INV-001, "2x Hijab Hitam" merujuk ke produk Hijab Hitam yang sama

### 🔗 **Hubungan Users ↔ Return Transactions (Pengguna ↔ Transaksi Return)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu karyawan bisa memproses banyak return, tetapi setiap return hanya diproses oleh satu karyawan.

**Contoh Konkret:**
- Manajer Ahmad hari ini approve: 3 return
- Kasir Fatimah hari ini terima: 2 return
- Return RTN-001 hanya diproses oleh Ahmad

### � **Hubungan Branches ↔ Return Transactions (Cabang ↔ Transaksi Return)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu cabang bisa memiliki banyak return, tetapi setiap return hanya terjadi di satu cabang.

**Contoh Konkret:**
- Cabang Jakarta hari ini: 5 return
- Cabang Bandung hari ini: 2 return
- Return Bu Sari hanya tercatat di cabang Jakarta

### 🔗 **Hubungan Transactions ↔ Return Transactions (Transaksi ↔ Transaksi Return)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu transaksi penjualan bisa di-return berkali-kali (partial return), tetapi setiap return merujuk ke satu transaksi asli.

**Contoh Konkret:**
- Transaksi INV-001 (3 item) bisa di-return:
  - Return pertama: 1 item (gamis)
  - Return kedua: 1 item (hijab)
- Tapi setiap return tetap merujuk ke transaksi INV-001

### � **Hubungan Return Transactions ↔ Return Items (Transaksi Return ↔ Detail Return)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu formulir return bisa berisi banyak barang, tetapi setiap barang dalam daftar return hanya milik satu formulir.

**Contoh Konkret:**
- Return RTN-001 berisi: 2 jenis barang (gamis + hijab)
- Return RTN-002 berisi: 1 jenis barang (rok)
- Tapi "1x Gamis Bordir" dalam RTN-001 tidak bisa pindah ke return lain

### 🔗 **Hubungan Products ↔ Return Items (Produk ↔ Detail Return)**
**Jenis:** One-to-Many (Satu ke Banyak)

**Penjelasan Sederhana:**
Satu produk bisa di-return berkali-kali oleh pelanggan berbeda, tetapi setiap item dalam return merujuk ke satu produk tertentu.

**Contoh Konkret:**
- Gamis Bordir di-return 3 kali bulan ini (masalah ukuran)
- Tapi dalam return RTN-001, "1x Gamis Bordir" merujuk ke produk Gamis Bordir yang sama

---

**Kesimpulan Relasi:**
Semua relasi dirancang untuk memastikan data tetap konsisten dan tidak ada duplikasi. Setiap tabel memiliki peran spesifik, dan hubungan antar tabel mengikuti logika bisnis toko pakaian muslim yang sesungguhnya.
