# Mapping Object-Component Documentation
## Sistem POS UMKM - Pemetaan Komponen Laravel MVC

### 📋 **Deskripsi Dokumen**
Dokumentasi ini menyediakan pemetaan lengkap antara fitur bisnis, komponen Laravel (Controllers, Models, Views), database tables, dan routes untuk sistem POS UMKM yang khusus melayani penjualan pakaian muslim wanita.

---

## 🏪 **Core Operations (Operasi Inti)**

| Fitur Bisnis | Controller | Model(s) | Database Table(s) | Key Routes | Blade Views | Keterangan |
|--------------|------------|----------|-------------------|------------|-------------|------------|
| **Transaksi Penjualan** | `Employee\TransactionController` | `Transaction`, `TransactionItem`, `Product` | `transactions`, `transaction_items`, `products` | `GET/POST /employee/transactions/*` | `employee/transactions/create.blade.php`, `employee/transactions/show.blade.php` | Junction table: transaction_items untuk detail produk |
| **Retur Produk** | `Manager\ReturnController` | `ReturnTransaction`, `ReturnItem`, `Product`, `DamagedStock` | `returns`, `return_items`, `products`, `damaged_stocks` | `GET/POST /manager/returns/*` | `manager/returns/index.blade.php`, `manager/returns/show.blade.php` | Status: pending, approved, rejected |
| **Manajemen Stok** | `Employee\StockController`, `Manager\ProductController` | `Product`, `Category`, `Branch` | `products`, `categories`, `branches` | `GET /employee/stocks`, `GET/POST /manager/products/*` | `employee/stocks/index.blade.php`, `manager/products/index.blade.php` | Real-time stock tracking dengan low stock alerts |

---

## 🔧 **Management Operations (Operasi Manajemen)**

| Fitur Bisnis | Controller | Model(s) | Database Table(s) | Key Routes | Blade Views | Keterangan |
|--------------|------------|----------|-------------------|------------|-------------|------------|
| **Kelola Produk** | `Manager\ProductController` | `Product`, `Category`, `Branch` | `products`, `categories`, `branches` | `GET/POST/PUT/DELETE /manager/products/*` | `manager/products/create.blade.php`, `manager/products/edit.blade.php` | CRUD lengkap dengan image upload |
| **Kelola Kategori** | `Manager\CategoryController` | `Category`, `Product` | `categories`, `products` | `GET/POST/PUT/DELETE /manager/categories/*` | `manager/categories/index.blade.php`, `manager/categories/create.blade.php` | Auto-generate slug dari nama kategori |
| **Kelola Pegawai** | `Manager\EmployeeController` | `User`, `Branch`, `Transaction` | `users`, `branches`, `transactions` | `GET/POST/PUT/DELETE /manager/employees/*` | `manager/employees/index.blade.php`, `manager/employees/create.blade.php` | Role: employee, dengan branch_id constraint |
| **Kelola Cabang** | `Director\BranchController` | `Branch`, `User`, `Product`, `Transaction` | `branches`, `users`, `products`, `transactions` | `GET/POST/PUT/DELETE /director/branches/*` | `director/branches/index.blade.php`, `director/branches/create.blade.php` | Soft deletes, operational_area field |
| **Kelola Pengguna** | `Director\UserController` | `User`, `Branch` | `users`, `branches` | `GET/POST/PUT/DELETE /director/users/*` | `director/users/index.blade.php`, `director/users/create.blade.php` | Multi-role: director, manager, employee |
| **Barang Rusak** | `Manager\DamagedStockController` | `DamagedStock`, `Product`, `ReturnItem` | `damaged_stocks`, `products`, `return_items` | `GET/POST /manager/damaged-stock/*` | `manager/damaged-stock/index.blade.php` | Tracking barang rusak dari retur |

---

## 📊 **Reports & Analytics (Laporan & Analitik)**

| Fitur Bisnis | Controller | Model(s) | Database Table(s) | Key Routes | Blade Views | Keterangan |
|--------------|------------|----------|-------------------|------------|-------------|------------|
| **Laporan Penjualan** | `Reports\ReportController` | `Transaction`, `TransactionItem`, `Product`, `Branch` | `transactions`, `transaction_items`, `products`, `branches` | `GET /director/reports/integrated`, `GET /manager/reports/integrated` | `director/reports/integrated.blade.php`, `manager/reports/integrated.blade.php` | Role-based access dengan filter periode |
| **Laporan Keuangan** | `Director\ReportController` | `Transaction`, `ReturnTransaction`, `Branch` | `transactions`, `returns`, `branches` | `GET /director/reports/keuangan` | `director/reports/keuangan.blade.php` | Net revenue calculation (gross - returns) |
| **Dashboard Direktur** | `Director\DashboardController` | `Branch`, `Transaction`, `User`, `ReturnTransaction` | `branches`, `transactions`, `users`, `returns` | `GET /director/dashboard` | `director/dashboard.blade.php` | Aggregated data dari semua cabang |
| **Dashboard Manajer** | `Manager\DashboardController` | `Transaction`, `Product`, `User`, `ReturnTransaction` | `transactions`, `products`, `users`, `returns` | `GET /manager/dashboard` | `manager/dashboard.blade.php` | Data specific untuk branch_id user |
| **Dashboard Pegawai** | `Employee\DashboardController` | `Transaction`, `Product`, `TransactionItem` | `transactions`, `products`, `transaction_items` | `GET /employee/dashboard` | `employee/dashboard.blade.php` | Personal performance metrics |

---

## 🔐 **Authentication & Authorization (Autentikasi & Otorisasi)**

| Fitur Bisnis | Controller | Model(s) | Database Table(s) | Key Routes | Blade Views | Keterangan |
|--------------|------------|----------|-------------------|------------|-------------|------------|
| **Autentikasi Pengguna** | `Auth\LoginController`, `Auth\RegisterController` | `User`, `Branch` | `users`, `branches` | `GET/POST /login`, `GET/POST /register`, `POST /logout` | `auth/login.blade.php`, `auth/register.blade.php` | Laravel Sanctum untuk API tokens |
| **Middleware Role** | `CheckRole` (Middleware) | `User` | `users` | Applied to route groups | N/A | Role-based access: director, manager, employee |
| **Middleware Branch** | `CheckBranchAccess` (Middleware) | `User`, `Branch` | `users`, `branches` | Applied to specific routes | N/A | Branch-specific data access control |

---

## 🗄️ **Database Schema Overview**

### **Core Tables:**
- **`users`**: id, name, email, password, role, branch_id, is_active
- **`branches`**: id, name, address, operational_area, phone, email, is_active
- **`categories`**: id, name, slug, description, is_active
- **`products`**: id, name, sku, category_id, description, image, base_price, stock, is_active, branch_id

### **Transaction Tables:**
- **`transactions`**: id, invoice_number, branch_id, employee_id, customer_name, subtotal, discount_amount, total_amount, payment_method, status
- **`transaction_items`**: id, transaction_id, product_id, quantity, price, discount_percentage, discount_amount, subtotal

### **Return Tables:**
- **`returns`**: id, return_number, branch_id, user_id, transaction_id, reason, total, status, approved_by, approved_at
- **`return_items`**: id, return_transaction_id, product_id, quantity, price, condition, reason
- **`damaged_stocks`**: id, branch_id, product_id, return_item_id, quantity, condition, reason, action_taken

---

## 🔗 **Key Relationships**

### **User Relationships:**
- `User` belongsTo `Branch`
- `User` hasMany `Transaction` (as employee)
- `User` hasMany `ReturnTransaction`

### **Product Relationships:**
- `Product` belongsTo `Category`
- `Product` belongsTo `Branch`
- `Product` hasMany `TransactionItem`
- `Product` hasMany `ReturnItem`

### **Transaction Relationships:**
- `Transaction` belongsTo `Branch`
- `Transaction` belongsTo `User` (as employee)
- `Transaction` hasMany `TransactionItem`
- `TransactionItem` belongsTo `Transaction`
- `TransactionItem` belongsTo `Product`

### **Return Relationships:**
- `ReturnTransaction` belongsTo `Branch`
- `ReturnTransaction` belongsTo `User`
- `ReturnTransaction` belongsTo `Transaction` (original)
- `ReturnTransaction` hasMany `ReturnItem`
- `ReturnItem` belongsTo `ReturnTransaction`
- `ReturnItem` belongsTo `Product`

---

## 📱 **View Structure**

### **Layout Files:**
- `layouts/app.blade.php` - Main application layout
- `layouts/auth.blade.php` - Authentication layout

### **Directory Structure:**
```
resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── director/
│   ├── dashboard.blade.php
│   ├── branches/
│   ├── users/
│   └── reports/
├── manager/
│   ├── dashboard.blade.php
│   ├── products/
│   ├── categories/
│   ├── employees/
│   ├── returns/
│   ├── damaged-stock/
│   └── reports/
└── employee/
    ├── dashboard.blade.php
    ├── transactions/
    └── stocks/
```

---

## 🛡️ **Middleware Configuration**

### **Route Protection:**
- `auth` - Requires authentication
- `CheckRole:director` - Director access only
- `CheckRole:manager` - Manager access only
- `CheckRole:employee` - Employee access only
- `CheckBranchAccess` - Branch-specific data access

### **Applied to Route Groups:**
- `/director/*` - auth + CheckRole:director
- `/manager/*` - auth + CheckRole:manager
- `/employee/*` - auth + CheckRole:employee

---

## 📝 **Business Logic Notes**

### **Stock Management:**
- Real-time stock updates pada setiap transaksi
- Low stock alerts (≤ 10 items)
- Stock validation sebelum transaksi

### **Return Process:**
- Manager approval required untuk semua retur
- Condition tracking: good, damaged
- Automatic stock adjustment untuk approved returns

### **Revenue Calculation:**
- Net Revenue = Gross Revenue - Approved Returns
- Branch-specific calculations
- Period-based filtering

### **Role-Based Access:**
- **Direktur**: Full system access, multi-branch reports
- **Manajer**: Branch-specific management, employee oversight
- **Pegawai**: Transaction processing, stock viewing only
