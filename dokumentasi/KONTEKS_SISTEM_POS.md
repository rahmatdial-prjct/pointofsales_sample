 Konteks Sistem POS UMKM
## Aplikasi Web Point of Sale untuk Usaha Pakaian Muslim Wanita

 📋 Ringkasan Dokumen
Dokumentasi komprehensif yang menjelaskan konteks bisnis, arsitektur sistem, dan operasional aplikasi web POS yang dirancang khusus untuk UMKM (Usaha Mikro, Kecil, dan Menengah) yang bergerak di bidang retail pakaian muslim wanita.

---

🏪 1. Business Context & Overview

 1.1 Tujuan Sistem
Sistem POS UMKM ini dikembangkan untuk memenuhi kebutuhan spesifik usaha retail pakaian muslim wanita dengan fokus pada:
- Manajemen Multi-Cabang: Pengelolaan operasional beberapa toko dalam satu sistem terpusat
- Kontrol Inventori Real-time: Tracking stok produk hijab, gamis, mukena, dan jilbab secara akurat
- Laporan Terintegrasi: Analisis performa penjualan dan keuangan lintas cabang
- Role-Based Management: Pembagian akses dan tanggung jawab berdasarkan hierarki organisasi

 1.2 Target Pengguna (UMKM)
Profil Bisnis:
- Usaha retail pakaian muslim wanita dengan 1-10 cabang
- Omzet bulanan Rp 50 juta - Rp 500 juta per cabang
- Tim operasional 3-15 orang per cabang
- Fokus produk: Hijab, Jilbab, Gamis, Mukena, Aksesoris Muslim

Karakteristik Operasional:
- Transaksi harian 20-100 per cabang
- Variasi produk 100-500 item per cabang
- Metode pembayaran: Tunai, Transfer Bank, QRIS, Dana
- Kebutuhan laporan harian, mingguan, dan bulanan

 1.3 Stakeholder Utama
Direktur (Pemilik Usaha):
- Mengawasi seluruh operasional multi-cabang
- Menganalisis performa bisnis dan profitabilitas
- Membuat keputusan strategis ekspansi dan investasi

Manajer Cabang:
- Mengelola operasional harian satu cabang
- Mengawasi kinerja pegawai dan target penjualan
- Mengelola inventori dan approval retur

Pegawai Toko:
- Melayani pelanggan dan memproses transaksi
- Mengelola display produk dan stock opname
- Membantu proses retur dan customer service

---

## 🔄 2. Business Processes

 2.1 Core Business Workflows

# Alur Transaksi Penjualan:
1. Penerimaan Pelanggan → Pegawai menyambut dan membantu pelanggan
2. Pencarian Produk → Menggunakan sistem untuk cek ketersediaan dan harga
3. Pemilihan Produk → Input produk ke keranjang dengan quantity dan diskon (max 80%)
4. Kalkulasi Total → Sistem menghitung subtotal, diskon, dan total pembayaran
5. Proses Pembayaran → Pilih metode pembayaran (tunai/transfer/QRIS/Dana)
6. Cetak Struk → Generate invoice dengan nomor unik per cabang
7. Update Stok → Otomatis mengurangi stok produk yang terjual

# Alur Proses Retur:
1. Penerimaan Retur → Pegawai menerima barang dan keluhan pelanggan
2. Verifikasi Produk → Cek kondisi barang (baik/rusak) dan nota pembelian
3. Input Data Retur → Catat alasan, kondisi, dan nilai retur
4. Approval Manajer → Manajer review dan approve/reject retur
5. Proses Refund → Jika disetujui, lakukan pengembalian uang
6. Update Inventori → Barang baik kembali ke stok, barang rusak ke damaged stock
7. Laporan Retur → Tercatat dalam laporan untuk analisis

 2.2 Role-Specific Processes

# Direktur - Strategic Management:
- Morning Routine: Review dashboard multi-cabang dan KPI harian
- Weekly Analysis: Analisis performa cabang dan identifikasi trend
- Monthly Planning: Evaluasi target, budget, dan strategi ekspansi
- Decision Making: Approval investasi, pembukaan cabang baru, hiring manager

# Manajer - Operational Management:
- Daily Operations: Monitor penjualan, stok, dan performa pegawai
- Inventory Control: Kelola stock opname, order produk baru, set harga
- Team Management: Schedule pegawai, training, dan performance review
- Customer Service: Handle komplain besar dan approval retur

# Pegawai - Front-line Operations:
- Customer Service: Melayani pelanggan, product knowledge, styling advice
- Transaction Processing: Input penjualan, handle pembayaran, cetak struk
- Inventory Support: Stock checking, display arrangement, basic stock count
- Administrative Tasks: Input retur, customer data, daily reporting

 2.3 Decision-Making Hierarchies

# Approval Workflows:
- Diskon > 50%: Memerlukan approval manajer
- Retur > Rp 500.000: Memerlukan approval manajer
- Void Transaction: Memerlukan approval manajer
- Penambahan Pegawai: Memerlukan approval direktur
- Pembukaan Cabang: Keputusan direktur

# Escalation Matrix:
- Level 1 (Pegawai): Transaksi normal, retur kecil, customer service basic
- Level 2 (Manajer): Komplain besar, retur mahal, diskon tinggi, void transaction
- Level 3 (Direktur): Keputusan strategis, investasi, ekspansi, hiring manager

---

## 🏗️ 3. System Architecture & Technical Overview

 3.1 Laravel MVC Structure

# Model Layer (Data Management):
- User Model: Manajemen pengguna dengan role-based permissions
- Branch Model: Data cabang dengan operational area tracking
- Product Model: Katalog produk dengan kategori dan pricing
- Transaction Model: Record penjualan dengan invoice numbering
- ReturnTransaction Model: Manajemen retur dengan approval workflow

# Controller Layer (Business Logic):
- Director Controllers: Multi-branch management dan strategic reporting
- Manager Controllers: Branch-specific operations dan team management
- Employee Controllers: Transaction processing dan customer service
- Report Controllers: Analytics dan financial reporting

# View Layer (User Interface):
- Responsive Design: Mobile-first approach untuk tablet dan smartphone
- Role-Based UI: Interface disesuaikan dengan permission level
- Indonesian Language: Konsisten menggunakan Bahasa Indonesia
- Modern Styling: Clean design dengan light blue color scheme

 3.2 Database Design

# Core Tables:
- users: Role-based user management (director/manager/employee)
- branches: Multi-location support dengan operational area
- products: Product catalog dengan category dan pricing
- transactions: Sales records dengan invoice numbering
- transaction_items: Detail item per transaksi dengan discount tracking

# Relationship Structure:
- User belongsTo Branch: Setiap user terikat pada satu cabang
- Product belongsTo Branch: Stok produk per cabang terpisah
- Transaction belongsTo Branch & User: Tracking penjualan per cabang dan pegawai
- ReturnTransaction belongsTo Transaction: Traceability retur ke transaksi asli

 3.3 Authentication & Authorization

# Role-Based Access Control:
- Director: Full system access, multi-branch data, strategic reports
- Manager: Branch-specific access, team management, operational reports
- Employee: Transaction processing, basic reporting, customer service

# Security Features:
- Laravel Sanctum: API token authentication
- Middleware Protection: Route-level access control
- Branch Isolation: Data separation antar cabang
- Audit Trail: Logging untuk transaction dan return activities

---

## ⚙️ 4. Operational Workflows

 4.1 Daily Operations by Role

# Direktur - Strategic Oversight:
Morning (08:00-10:00):
- Review dashboard multi-cabang
- Analisis sales performance vs target
- Check critical alerts (low stock, high returns)

Afternoon (14:00-16:00):
- Review laporan harian per cabang
- Analisis trend penjualan dan customer behavior
- Meeting dengan manajer untuk performance review

Evening (18:00-20:00):
- Finalisasi laporan harian
- Planning untuk hari berikutnya
- Review financial summary dan cash flow

# Manajer - Operational Management:
Opening (08:00-09:00):
- Check sistem dan koneksi
- Review stock level dan reorder points
- Brief pegawai untuk target harian

Peak Hours (10:00-17:00):
- Monitor real-time sales performance
- Handle customer complaints dan approval requests
- Manage inventory dan product display

Closing (18:00-20:00):
- Reconcile cash dan payment methods
- Generate daily reports
- Plan next day operations

# Pegawai - Customer Service:
Opening (08:00-09:00):
- Setup POS system dan check connectivity
- Arrange product display
- Prepare for customer service

Service Hours (09:00-18:00):
- Process customer transactions
- Handle product inquiries dan styling advice
- Manage returns dan customer complaints

Closing (18:00-19:00):
- Count cash register
- Update stock display
- Submit daily transaction summary

 4.2 Transaction Processing Flow

# Standard Sale Process:
1. Customer Greeting → Welcome dan product assistance
2. Product Selection → Search system, check availability, show options
3. Cart Management → Add items, apply discounts (max 80%), calculate total
4. Payment Processing → Choose method, process payment, verify amount
5. Invoice Generation → Print receipt dengan unique invoice number
6. Stock Update → Automatic inventory adjustment
7. Customer Follow-up → Thank customer, provide care instructions

# Payment Method Handling:
- Cash: Count money, provide change, record in cash register
- Transfer Bank: Verify transfer receipt, match amount, confirm payment
- QRIS: Scan QR code, wait confirmation, verify transaction
- Dana: Process e-wallet payment, confirm transaction status

 4.3 Return/Refund Procedures

# Return Acceptance Criteria:
- Time Limit: Maksimal 7 hari dari tanggal pembelian
- Condition: Produk dalam kondisi baik, tag masih terpasang
- Documentation: Nota pembelian asli atau invoice number
- Reason: Alasan yang valid (salah ukuran, cacat produk, tidak sesuai)

# Return Processing Steps:
1. Initial Assessment → Pegawai check kondisi dan dokumentasi
2. System Lookup → Cari transaksi asli berdasarkan invoice
3. Condition Evaluation → Tentukan kondisi: baik/rusak
4. Manager Approval → Untuk retur > Rp 200.000 atau kondisi khusus
5. Refund Processing → Proses pengembalian sesuai metode pembayaran asli
6. Inventory Update → Update stok dan damaged stock jika perlu
7. Documentation → Record dalam sistem untuk tracking dan analysis

---

## 🎯 5. System Features & Capabilities

 5.1 Feature Breakdown by User Role

# Direktur Features:
- Multi-Branch Dashboard: Real-time overview semua cabang
- Integrated Reporting: Consolidated financial dan operational reports
- Branch Management: CRUD operations untuk data cabang
- User Management: Manage manajer dan pegawai across branches
- Strategic Analytics: Trend analysis, forecasting, ROI calculation
- Export Capabilities: Excel/PDF export untuk semua laporan

# Manajer Features:
- Branch Dashboard: Detailed view untuk cabang yang dikelola
- Product Management: CRUD produk, kategori, pricing, stock control
- Employee Management: Manage pegawai dalam cabang
- Return Approval: Review dan approve/reject return requests
- Inventory Control: Stock opname, reorder management, damaged stock tracking
- Branch Reporting: Sales, inventory, employee performance reports

# Pegawai Features:
- Transaction Processing: POS interface untuk penjualan
- Product Lookup: Search dan check availability produk
- Customer Management: Basic customer data dan transaction history
- Return Processing: Input return requests untuk manager approval
- Stock Inquiry: View current stock levels dan product information
- Personal Dashboard: Individual sales performance dan targets

 5.2 Integration Capabilities

# Payment Integration:
- Bank Transfer: Manual verification dengan upload bukti transfer
- QRIS: Integration dengan payment gateway untuk QR code
- E-Wallet: Support untuk Dana, OVO, GoPay (manual verification)
- Cash Management: Built-in cash register dengan reconciliation

# Reporting Integration:
- Excel Export: Automated report generation dalam format Excel
- PDF Generation: Professional invoice dan report formatting
- Email Notifications: Automated alerts untuk low stock, high returns
- WhatsApp Integration: Customer notification untuk promo dan updates (future)

 5.3 Mobile Responsiveness

# Responsive Design Features:
- Mobile-First Approach: Optimized untuk tablet dan smartphone usage
- Touch-Friendly Interface: Large buttons, easy navigation untuk touch screen
- Offline Capability: Basic transaction processing saat koneksi terbatas
- Progressive Web App: Install sebagai app di mobile device

# Mobile-Specific Functions:
- Barcode Scanning: Camera-based product lookup (future enhancement)
- Mobile Payment: Integration dengan mobile payment methods
- Location Services: GPS tracking untuk delivery dan field sales
- Push Notifications: Real-time alerts untuk managers dan directors

---

## 📊 6. Business Rules & Logic

 6.1 Inventory Management Rules

# Stock Control Policies:
- Minimum Stock Level: Alert ketika stok ≤ 10 items per produk
- Automatic Reorder: Suggestion untuk reorder berdasarkan sales velocity
- Stock Reservation: Hold stock untuk pending transactions (15 menit)
- Damaged Stock Tracking: Separate tracking untuk barang rusak dari retur

# Stock Movement Tracking:
- Sales Deduction: Otomatis kurangi stok saat transaksi completed
- Return Addition: Tambah stok untuk approved returns dengan kondisi baik
- Damaged Segregation: Pisahkan damaged stock untuk disposal tracking
- Audit Trail: Complete log semua stock movements dengan timestamp

 6.2 Pricing and Discount Policies

# Pricing Structure:
- Base Price: Harga dasar produk yang ditetapkan manajer
- Dynamic Pricing: Flexibility untuk adjust harga per cabang
- Bulk Discount: Otomatis discount untuk pembelian quantity tertentu
- Seasonal Pricing: Special pricing untuk event dan season tertentu

# Discount Rules:
- Maximum Discount: 80% dari harga dasar per item
- Manager Approval: Required untuk discount > 50%
- Cumulative Discount: Tidak boleh exceed 80% total
- Promotional Discount: Special rates untuk member atau event

 6.3 Return Policies and Conditions

# Return Eligibility:
- Time Frame: Maksimal 7 hari dari tanggal pembelian
- Product Condition: Barang dalam kondisi baik, tag terpasang, tidak dicuci
- Documentation: Nota pembelian atau invoice number wajib
- Exclusions: Underwear, sale items, custom orders tidak bisa diretur

# Return Processing Rules:
- Manager Approval: Wajib untuk semua retur > Rp 200.000
- Condition Assessment: Baik (kembali ke stok) vs Rusak (ke damaged stock)
- Refund Method: Sesuai metode pembayaran asli (cash→cash, transfer→transfer)
- Partial Return: Allowed untuk multi-item transactions

 6.4 Revenue Calculation Methods

# Gross vs Net Revenue:
- Gross Revenue: Total penjualan sebelum dikurangi retur
- Net Revenue: Gross revenue minus approved returns dalam periode yang sama
- Return Rate: Persentase retur terhadap total penjualan
- Profitability: Net revenue minus cost of goods sold (COGS)

# Financial Reporting Logic:
- Daily Reconciliation: Match cash register dengan system records
- Period-Based Calculation: Revenue dan returns dalam periode yang sama
- Branch Comparison: Standardized metrics untuk compare performance
- Trend Analysis: Month-over-month dan year-over-year comparison

# Performance Metrics:
- Sales per Employee: Individual dan team performance tracking
- Average Transaction Value: Monitor customer spending patterns
- Product Velocity: Fast-moving vs slow-moving inventory analysis
- Customer Retention: Repeat customer tracking dan loyalty metrics

---

## 🎯 Kesimpulan

Sistem POS UMKM ini dirancang khusus untuk memenuhi kebutuhan bisnis retail pakaian muslim wanita dengan fokus pada:
- Operasional Efisien: Streamlined processes untuk daily operations
- Multi-Branch Management: Centralized control dengan branch autonomy
- Role-Based Access: Appropriate permissions untuk setiap level user
- Real-Time Analytics: Data-driven decision making capabilities
- Scalable Architecture: Ready untuk business growth dan expansion

Sistem ini telah 96% selesai dan dalam tahap final cleanup, siap untuk deployment dan operasional penuh.
