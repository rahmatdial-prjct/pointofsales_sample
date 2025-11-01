 Konteks Database Tables
 Dokumentasi Struktur Database Sistem POS UMKM

 📋 Ringkasan Dokumen
Dokumentasi komprehensif yang menjelaskan struktur database, relasi antar tabel, dan operasi bisnis untuk sistem POS UMKM yang khusus melayani retail pakaian muslim wanita.

---

 🏗️ Core System Tables (Sistem Inti)

 User
Konteks: Manajemen pengguna dengan role-based access control untuk direktur, manajer, dan pegawai.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- name: string(255) - Nama lengkap pengguna
- email: string(255) - Email unik untuk login
- password: string(255) - Password terenkripsi
- role: enum('director', 'manager', 'employee') - Role pengguna
- is_active: boolean - Status aktif pengguna
- branch_id: bigint - Foreign key ke tabel branches
- email_verified_at: datetime - Timestamp verifikasi email
- remember_token: string(100) - Token untuk remember me
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir

 Operations:
+ isDirector(): boolean - Cek apakah user adalah direktur
+ isManager(): boolean - Cek apakah user adalah manajer
+ isEmployee(): boolean - Cek apakah user adalah pegawai
+ isActive(): boolean - Cek status aktif user
+ canManageUsers(): boolean - Cek permission manage users
+ canManageProducts(): boolean - Cek permission manage products
+ canManageStock(): boolean - Cek permission manage stock
+ canProcessTransactions(): boolean - Cek permission process transactions
+ canProcessReturns(): boolean - Cek permission process returns
+ canViewReports(): boolean - Cek permission view reports
+ scopeActive(query): Query - Scope untuk user aktif
+ scopeByRole(query, role): Query - Scope berdasarkan role
+ scopeByBranch(query, branchId): Query - Scope berdasarkan cabang

 Relationships:
- belongsTo: Branch - User terikat pada satu cabang
- hasMany: Transaction (as employee) - User sebagai pegawai yang melakukan transaksi
- hasMany: ReturnTransaction - User yang memproses retur

---

 Branch
Konteks: Data cabang toko untuk operasional multi-location dengan area operasional tracking.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- name: string(255) - Nama cabang
- address: text - Alamat lengkap cabang
- operational_area: string(255) - Area operasional (kota/wilayah)
- phone: string(20) - Nomor telepon cabang
- email: string(255) - Email cabang
- is_active: boolean - Status aktif cabang
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ scopeActive(query): Query - Scope untuk cabang aktif
+ getTotalRevenue(): decimal - Hitung total revenue cabang
+ getActiveEmployeesCount(): integer - Hitung jumlah pegawai aktif

 Relationships:
- hasMany: User - Pegawai yang bekerja di cabang
- hasMany: Product - Produk yang tersedia di cabang
- hasMany: Transaction - Transaksi yang terjadi di cabang
- hasMany: ReturnTransaction - Retur yang terjadi di cabang
- hasMany: DamagedStock - Barang rusak di cabang

---

 📦 Product Management Tables (Manajemen Produk)

 Category
Konteks: Kategori produk pakaian muslim (Hijab, Gamis, Mukena, Jilbab, Aksesoris).

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- name: string(255) - Nama kategori
- slug: string(255) - URL-friendly slug
- description: text - Deskripsi kategori
- is_active: boolean - Status aktif kategori
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ scopeActive(query): Query - Scope untuk kategori aktif
+ getProductsCount(): integer - Hitung jumlah produk dalam kategori
+ boot(): void - Auto-generate slug dari nama

 Relationships:
- hasMany: Product - Produk dalam kategori

---

 Product
Konteks: Katalog produk pakaian muslim dengan stok per cabang dan pricing management.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- name: string(255) - Nama produk
- sku: string(255) - Stock Keeping Unit (unik)
- category_id: bigint - Foreign key ke tabel categories
- description: text - Deskripsi produk
- image: string(255) - Path gambar produk
- base_price: decimal(12,2) - Harga dasar produk
- stock: integer - Jumlah stok tersedia
- is_active: boolean - Status aktif produk
- branch_id: bigint - Foreign key ke tabel branches
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ scopeActive(query): Query - Scope untuk produk aktif
+ scopeLowStock(query): Query - Scope untuk produk stok rendah (≤10)
+ scopeByBranch(query, branchId): Query - Scope berdasarkan cabang
+ isLowStock(): boolean - Cek apakah stok rendah
+ getActualStockMovements(): array - Hitung pergerakan stok aktual
+ updateStock(quantity): void - Update stok produk

 Relationships:
- belongsTo: Category - Kategori produk
- belongsTo: Branch - Cabang tempat produk tersedia
- hasMany: TransactionItem - Item dalam transaksi
- hasMany: ReturnItem - Item dalam retur
- hasMany: DamagedStock - Barang rusak

---

 💰 Transaction System Tables (Sistem Transaksi)

 Transaction
Konteks: Record penjualan dengan invoice numbering dan payment method tracking.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- invoice_number: string(255) - Nomor invoice unik per cabang
- branch_id: bigint - Foreign key ke tabel branches
- user_id: bigint - Foreign key ke tabel users (backward compatibility)
- employee_id: bigint - Foreign key ke tabel users (pegawai yang melayani)
- customer_name: string(255) - Nama pelanggan
- subtotal: decimal(12,2) - Subtotal sebelum diskon
- discount_amount: decimal(12,2) - Total diskon
- total_amount: decimal(12,2) - Total setelah diskon
- payment_method: enum('cash', 'transfer', 'qris', 'dana', 'other') - Metode pembayaran
- status: enum('pending', 'completed', 'cancelled') - Status transaksi
- notes: text - Catatan tambahan
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ calculateTotal(): decimal - Hitung total transaksi
+ scopeCompleted(query): Query - Scope untuk transaksi selesai
+ scopeByBranch(query, branchId): Query - Scope berdasarkan cabang
+ scopeByEmployee(query, employeeId): Query - Scope berdasarkan pegawai
+ scopeByDateRange(query, startDate, endDate): Query - Scope berdasarkan periode
+ generateInvoiceNumber(): string - Generate nomor invoice unik

 Relationships:
- belongsTo: Branch - Cabang tempat transaksi
- belongsTo: User (as employee) - Pegawai yang melayani
- belongsTo: User (as user) - User yang melakukan transaksi
- hasMany: TransactionItem - Item dalam transaksi
- hasOne: ReturnTransaction - Retur dari transaksi ini

---

 TransactionItem
Konteks: Detail item per transaksi dengan discount tracking dan subtotal calculation.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- transaction_id: bigint - Foreign key ke tabel transactions
- product_id: bigint - Foreign key ke tabel products
- quantity: integer - Jumlah item
- price: decimal(12,2) - Harga per item saat transaksi
- discount_percentage: decimal(5,2) - Persentase diskon (max 80%)
- discount_amount: decimal(12,2) - Nominal diskon
- subtotal: decimal(12,2) - Subtotal item (price  quantity - discount)
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir

 Operations:
+ calculateSubtotal(): decimal - Hitung subtotal item
+ applyDiscount(percentage): void - Terapkan diskon
+ getDiscountedPrice(): decimal - Hitung harga setelah diskon

 Relationships:
- belongsTo: Transaction - Transaksi induk
- belongsTo: Product - Produk yang dibeli

---

 🔄 Return System Tables (Sistem Retur)

 ReturnTransaction
Konteks: Manajemen retur dengan approval workflow dan status tracking.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- return_number: string(255) - Nomor retur unik
- branch_id: bigint - Foreign key ke tabel branches
- user_id: bigint - Foreign key ke tabel users (yang memproses)
- transaction_id: bigint - Foreign key ke tabel transactions (transaksi asli)
- reason: text - Alasan retur
- total: decimal(12,2) - Total nilai retur
- status: enum('menunggu', 'disetujui', 'ditolak') - Status approval
- approved_by: bigint - Foreign key ke tabel users (manajer yang approve)
- approved_at: datetime - Timestamp approval
- notes: text - Catatan tambahan
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ approve(managerId): boolean - Approve retur
+ reject(managerId, reason): boolean - Tolak retur
+ calculateTotal(): decimal - Hitung total retur
+ scopePending(query): Query - Scope untuk retur menunggu
+ scopeApproved(query): Query - Scope untuk retur disetujui
+ generateReturnNumber(): string - Generate nomor retur unik

 Relationships:
- belongsTo: Branch - Cabang tempat retur
- belongsTo: User (as employee) - Pegawai yang memproses
- belongsTo: User (as approver) - Manajer yang approve
- belongsTo: Transaction - Transaksi asli
- hasMany: ReturnItem - Item yang diretur

---

 ReturnItem
Konteks: Detail item retur dengan condition tracking (baik/rusak) dan reason.

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- return_transaction_id: bigint - Foreign key ke tabel returns
- product_id: bigint - Foreign key ke tabel products
- quantity: integer - Jumlah item diretur
- price: decimal(12,2) - Harga item saat retur
- subtotal: decimal(12,2) - Subtotal retur item
- reason: text - Alasan spesifik item
- condition: enum('good', 'damaged') - Kondisi barang
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir

 Operations:
+ calculateSubtotal(): void - Hitung subtotal retur
+ isGoodCondition(): boolean - Cek kondisi baik
+ isDamaged(): boolean - Cek kondisi rusak

 Relationships:
- belongsTo: ReturnTransaction - Retur induk
- belongsTo: Product - Produk yang diretur
- hasOne: DamagedStock - Barang rusak (jika condition = damaged)

---

 🗑️ Supporting Tables (Tabel Pendukung)

 DamagedStock
Konteks: Tracking barang rusak dari retur dengan action management (repair/dispose/return to supplier).

 Attributes:
- id: bigint (Primary Key, Auto Increment)
- branch_id: bigint - Foreign key ke tabel branches
- product_id: bigint - Foreign key ke tabel products
- return_item_id: bigint - Foreign key ke tabel return_items
- quantity: integer - Jumlah barang rusak
- condition: enum('damaged', 'defective') - Jenis kerusakan
- reason: text - Alasan kerusakan
- action_taken: enum('repair', 'dispose', 'return_to_supplier') - Tindakan yang diambil
- disposed_at: datetime - Timestamp disposal
- disposed_by: bigint - Foreign key ke tabel users (yang dispose)
- notes: text - Catatan tambahan
- created_at: datetime - Timestamp pembuatan
- updated_at: datetime - Timestamp update terakhir
- deleted_at: datetime - Soft delete timestamp

 Operations:
+ scopeDamaged(query): Query - Scope untuk barang rusak
+ scopeDefective(query): Query - Scope untuk barang cacat
+ scopePending(query): Query - Scope untuk menunggu tindakan
+ scopeDisposed(query): Query - Scope untuk sudah dibuang
+ markAsRepaired(): void - Tandai sebagai diperbaiki
+ markAsDisposed(userId): void - Tandai sebagai dibuang
+ returnToSupplier(): void - Kembalikan ke supplier

 Relationships:
- belongsTo: Branch - Cabang tempat barang rusak
- belongsTo: Product - Produk yang rusak
- belongsTo: ReturnItem - Item retur yang menyebabkan damaged stock
- belongsTo: User (as disposedBy) - User yang melakukan disposal

---

 🔗 Database Relationships Summary

 Primary Relationships:
- User → Branch: Many-to-One (Setiap user terikat pada satu cabang)
- Product → Category: Many-to-One (Setiap produk memiliki satu kategori)
- Product → Branch: Many-to-One (Setiap produk terikat pada satu cabang)
- Transaction → Branch: Many-to-One (Setiap transaksi terjadi di satu cabang)
- Transaction → User: Many-to-One (Setiap transaksi dilayani oleh satu pegawai)

 Transaction Flow Relationships:
- Transaction → TransactionItem: One-to-Many (Satu transaksi banyak item)
- TransactionItem → Product: Many-to-One (Setiap item merujuk satu produk)
- ReturnTransaction → Transaction: Many-to-One (Retur merujuk transaksi asli)
- ReturnTransaction → ReturnItem: One-to-Many (Satu retur banyak item)
- ReturnItem → DamagedStock: One-to-One (Item rusak masuk damaged stock)

 Foreign Key Constraints:
- ON DELETE RESTRICT: Mencegah penghapusan data yang masih direferensi
- Soft Deletes: Menggunakan deleted_at untuk penghapusan logis
- Unique Constraints: SKU produk, email user, slug kategori

 Indexes:
- Primary Keys: Auto-increment pada semua tabel
- Foreign Keys: Index otomatis untuk performa join
- Unique Indexes: SKU, email, slug untuk data integrity
- Composite Indexes: (branch_id, created_at) untuk laporan per cabang

---

 📊 Business Logic Implementation

 Stock Management:
- Real-time Updates: Stok berkurang otomatis saat transaksi completed
- Return Handling: Stok bertambah untuk approved returns dengan kondisi baik
- Damaged Tracking: Barang rusak masuk ke damaged_stocks, tidak kembali ke stok
- Low Stock Alerts: Sistem alert ketika stok ≤ 10 items

 Revenue Calculation:
- Gross Revenue: Sum dari total_amount transactions dengan status completed
- Net Revenue: Gross revenue minus total approved returns dalam periode sama
- Return Rate: Persentase nilai retur terhadap total penjualan

 Data Integrity:
- Soft Deletes: Semua tabel utama menggunakan soft delete untuk audit trail
- Foreign Key Constraints: Mencegah orphaned records
- Enum Validations: Status dan kondisi terbatas pada nilai yang valid
- Decimal Precision: 2 digit desimal untuk semua nilai mata uang
