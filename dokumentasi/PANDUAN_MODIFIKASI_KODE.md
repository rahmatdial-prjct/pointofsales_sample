# Panduan Modifikasi Kode
## Sistem POS UMKM - Manual Perubahan Kode Laravel

### 📋 **Deskripsi Dokumen**
Panduan lengkap untuk melakukan modifikasi kode pada sistem POS UMKM dengan instruksi step-by-step, lokasi file spesifik, dan contoh kode sebelum/sesudah perubahan.

---

## 🔧 **Simple Modifications (Modifikasi Sederhana)**

### **1. Mengubah Tipe Input dari Angka ke Teks**

#### **Contoh: Mengubah Field Stock dari Number ke Text**

**Lokasi File:** `resources/views/manager/products/create.blade.php`
**Baris Kode:** Cari `//INPUT_TYPE_STOCK//`
```html
<!-- BEFORE: -->
<input type="number" name="stock" id="stock" class="form-control" min="0" required>

<!-- AFTER: -->
<input type="text" name="stock" id="stock" class="form-control" required>
```

**Lokasi File:** `app/Http/Requests/ProductRequest.php` (jika ada) atau `app/Http/Controllers/Manager/ProductController.php`
**Baris Kode:** Cari `//VALIDATION_RULES_STOCK//`
```php
// BEFORE:
'stock' => 'required|numeric|min:0',

// AFTER:
'stock' => 'required|string|max:255',
```

**Lokasi File:** `database/migrations/xxxx_create_products_table.php`
**Baris Kode:** Cari `//COLUMN_TYPE_STOCK//`
```php
// BEFORE:
$table->integer('stock')->default(0);

// AFTER: (Buat migration baru)
$table->string('stock')->default('0');
```

**Migration Command:**
```bash
php artisan make:migration change_stock_column_to_string_in_products_table
```

---

### **2. Mengubah Label dan Teks Interface**

#### **Contoh: Mengubah "Product" menjadi "Produk Hijab"**

**Lokasi File:** `resources/views/manager/products/index.blade.php`
**Baris Kode:** Cari `//PAGE_TITLE//`
```html
<!-- BEFORE: -->
<h1>Manajemen Produk</h1>

<!-- AFTER: -->
<h1>Manajemen Produk Hijab</h1>
```

**Lokasi File:** `resources/views/layouts/app.blade.php`
**Baris Kode:** Cari `//NAV_PRODUCT_LABEL//`
```html
<!-- BEFORE: -->
<span class="nav-text">Manajemen Produk</span>

<!-- AFTER: -->
<span class="nav-text">Manajemen Produk Hijab</span>
```

---

### **3. Mengubah Validation Rules**

#### **Contoh: Mengubah Minimum Stock dari 0 ke 5**

**Lokasi File:** `app/Http/Controllers/Manager/ProductController.php`
**Baris Kode:** Cari `//VALIDATION_MIN_STOCK//`
```php
// BEFORE:
$request->validate([
    'stock' => 'required|numeric|min:0',
]);

// AFTER:
$request->validate([
    'stock' => 'required|numeric|min:5',
]);
```

---

## 🔄 **Intermediate Modifications (Modifikasi Menengah)**

### **4. Menambah Field Baru ke Form**

#### **Contoh: Menambah Field "Supplier" ke Product**

**Step 1: Database Migration**
```bash
php artisan make:migration add_supplier_to_products_table
```

**Lokasi File:** `database/migrations/xxxx_add_supplier_to_products_table.php`
```php
// MIGRATION_CONTENT:
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('supplier')->nullable()->after('description'); //NEW_FIELD_SUPPLIER//
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('supplier'); //ROLLBACK_SUPPLIER//
    });
}
```

**Step 2: Model Update**
**Lokasi File:** `app/Models/Product.php`
**Baris Kode:** Cari `//FILLABLE_DEFINITION//`
```php
// BEFORE:
protected $fillable = [
    'name', 'sku', 'category_id', 'description', 'image', 'base_price', 'stock', 'is_active', 'branch_id',
];

// AFTER:
protected $fillable = [
    'name', 'sku', 'category_id', 'description', 'supplier', 'image', 'base_price', 'stock', 'is_active', 'branch_id', //ADDED_SUPPLIER//
];
```

**Step 3: Form Update**
**Lokasi File:** `resources/views/manager/products/create.blade.php`
**Baris Kode:** Cari `//FORM_FIELDS_SECTION//` dan tambahkan setelah description
```html
<!-- ADD_AFTER_DESCRIPTION: -->
<div class="form-group">
    <label for="supplier">Supplier</label> <!--NEW_SUPPLIER_FIELD-->
    <input type="text" name="supplier" id="supplier" class="form-control" placeholder="Nama supplier produk">
</div>
```

**Step 4: Controller Update**
**Lokasi File:** `app/Http/Controllers/Manager/ProductController.php`
**Baris Kode:** Cari `//VALIDATION_RULES//`
```php
// ADD_TO_VALIDATION:
$request->validate([
    // ... existing rules
    'supplier' => 'nullable|string|max:255', //NEW_SUPPLIER_VALIDATION//
]);
```

---

### **5. Mengubah Dropdown Options**

#### **Contoh: Menambah Payment Method "Dana" ke Transaksi**

**Step 1: Database Migration**
**Lokasi File:** `database/migrations/xxxx_update_payment_methods_in_transactions.php`
```php
// MIGRATION_ENUM_UPDATE:
public function up()
{
    DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash', 'transfer', 'qris', 'dana', 'other')"); //UPDATED_ENUM//
}
```

**Step 2: View Update**
**Lokasi File:** `resources/views/employee/transactions/create.blade.php`
**Baris Kode:** Cari `//PAYMENT_METHOD_OPTIONS//`
```html
<!-- BEFORE: -->
<select name="payment_method" class="form-control" required>
    <option value="cash">Tunai</option>
    <option value="transfer">Transfer Bank</option>
    <option value="qris">QRIS</option>
    <option value="other">Lainnya</option>
</select>

<!-- AFTER: -->
<select name="payment_method" class="form-control" required>
    <option value="cash">Tunai</option>
    <option value="transfer">Transfer Bank</option>
    <option value="qris">QRIS</option>
    <option value="dana">Dana</option> <!--NEW_DANA_OPTION-->
    <option value="other">Lainnya</option>
</select>
```

---

## 🚀 **Advanced Modifications (Modifikasi Lanjutan)**

### **6. Menambah Controller dan Route Baru**

#### **Contoh: Menambah Fitur "Wishlist" untuk Customer**

**Step 1: Create Model dan Migration**
```bash
php artisan make:model Wishlist -m
```

**Lokasi File:** `app/Models/Wishlist.php`
```php
// MODEL_DEFINITION:
class Wishlist extends Model
{
    protected $fillable = ['customer_email', 'product_id', 'branch_id']; //WISHLIST_FILLABLE//
    
    public function product() {
        return $this->belongsTo(Product::class); //WISHLIST_PRODUCT_RELATION//
    }
}
```

**Step 2: Create Controller**
```bash
php artisan make:controller Employee/WishlistController
```

**Lokasi File:** `app/Http/Controllers/Employee/WishlistController.php`
```php
// CONTROLLER_METHODS:
public function index() {
    $wishlists = Wishlist::with('product')->where('branch_id', Auth::user()->branch_id)->get(); //WISHLIST_INDEX//
    return view('employee.wishlists.index', compact('wishlists'));
}

public function store(Request $request) {
    $request->validate(['customer_email' => 'required|email', 'product_id' => 'required|exists:products,id']); //WISHLIST_VALIDATION//
    Wishlist::create($request->all() + ['branch_id' => Auth::user()->branch_id]);
    return redirect()->back()->with('success', 'Produk ditambahkan ke wishlist');
}
```

**Step 3: Add Routes**
**Lokasi File:** `routes/web.php`
**Baris Kode:** Cari `//EMPLOYEE_ROUTES_SECTION//`
```php
// ADD_TO_EMPLOYEE_ROUTES:
Route::resource('wishlists', \App\Http\Controllers\Employee\WishlistController::class)->only(['index', 'store', 'destroy']); //NEW_WISHLIST_ROUTES//
```

**Step 4: Create Views**
```bash
mkdir resources/views/employee/wishlists
touch resources/views/employee/wishlists/index.blade.php
```

---

### **7. Mengubah Database Relationship**

#### **Contoh: Menambah Many-to-Many Relationship antara Product dan Tag**

**Step 1: Create Tag Model dan Pivot Table**
```bash
php artisan make:model Tag -m
php artisan make:migration create_product_tag_table
```

**Lokasi File:** `app/Models/Tag.php`
```php
// TAG_MODEL:
class Tag extends Model {
    protected $fillable = ['name', 'color']; //TAG_FILLABLE//
    
    public function products() {
        return $this->belongsToMany(Product::class); //TAG_PRODUCTS_RELATION//
    }
}
```

**Step 2: Update Product Model**
**Lokasi File:** `app/Models/Product.php`
**Baris Kode:** Cari `//RELATIONSHIPS_SECTION//`
```php
// ADD_TO_RELATIONSHIPS:
public function tags() {
    return $this->belongsToMany(Tag::class); //PRODUCT_TAGS_RELATION//
}
```

**Step 3: Pivot Table Migration**
**Lokasi File:** `database/migrations/xxxx_create_product_tag_table.php`
```php
// PIVOT_TABLE_STRUCTURE:
public function up() {
    Schema::create('product_tag', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); //PRODUCT_FK//
        $table->foreignId('tag_id')->constrained()->onDelete('cascade'); //TAG_FK//
        $table->timestamps();
    });
}
```

---

## ⚠️ **Rollback Instructions (Instruksi Rollback)**

### **Rollback Database Changes:**
```bash
# Rollback last migration
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --path=/database/migrations/xxxx_specific_migration.php

# Rollback multiple steps
php artisan migrate:rollback --step=3
```

### **Rollback Code Changes:**
1. **Git Restore:** `git checkout -- filename.php`
2. **Manual Revert:** Gunakan komentar `//BEFORE:` dan `//AFTER:` untuk mengembalikan kode
3. **Backup Files:** Selalu backup file sebelum modifikasi

---

## 🔍 **Verification Steps (Langkah Verifikasi)**

### **After Each Modification:**
1. **Clear Cache:** `php artisan cache:clear`
2. **Clear Config:** `php artisan config:clear`
3. **Clear Views:** `php artisan view:clear`
4. **Run Migrations:** `php artisan migrate`
5. **Test Functionality:** Manual testing pada browser
6. **Check Logs:** `tail -f storage/logs/laravel.log`

### **Common Issues & Solutions:**
- **500 Error:** Check `storage/logs/laravel.log`
- **Migration Error:** Check foreign key constraints
- **Validation Error:** Verify validation rules match form fields
- **Route Error:** Run `php artisan route:clear`

---

## 📝 **Comment Markers Reference**

### **Standard Markers untuk Lokasi Kode:**
- `//FILLABLE_DEFINITION//` - Model fillable array
- `//VALIDATION_RULES//` - Controller validation
- `//INPUT_TYPE_DEFINITION//` - Form input types
- `//COLUMN_TYPE_DEFINITION//` - Migration column types
- `//RELATIONSHIPS_SECTION//` - Model relationships
- `//FORM_FIELDS_SECTION//` - View form fields
- `//ROUTES_SECTION//` - Route definitions
- `//PAGE_TITLE//` - Page headings
- `//NAV_LABELS//` - Navigation labels

### **Change Markers:**
- `//BEFORE:` - Kode sebelum perubahan
- `//AFTER:` - Kode setelah perubahan
- `//NEW_FEATURE//` - Fitur baru yang ditambahkan
- `//UPDATED_LOGIC//` - Logic yang diperbarui
- `//ROLLBACK_POINT//` - Titik untuk rollback
