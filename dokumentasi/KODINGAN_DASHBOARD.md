# Dokumentasi Kodingan Dashboard - Sistem POS UMKM
## Implementasi Fitur Dashboard Multi-Role

### 📋 **Ringkasan Dokumen**
Dokumentasi ini berisi kodingan penting untuk implementasi fitur dashboard dengan 3 role berbeda: Direktur, Manajer, dan Pegawai. Setiap role memiliki dashboard yang disesuaikan dengan kebutuhan dan kewenangan masing-masing.

---

## 🏗️ **1. Model Layer**

### **Model Utama untuk Dashboard:**

#### **User Model (app/Models/User.php)**
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'branch_id', 'is_active'
    ];

    // Relationships untuk Dashboard
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Role Check Methods
    public function isDirector()
    {
        return $this->role === 'director';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}
```

#### **Transaction Model (app/Models/Transaction.php)**
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'branch_id', 'user_id', 'employee_id',
        'customer_name', 'subtotal', 'discount_amount', 'total_amount',
        'payment_method', 'status', 'notes'
    ];

    // Relationships untuk Dashboard
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Scopes untuk Dashboard Queries
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }
}
```

---

## 🎛️ **2. Controller Layer**

### **Dashboard Controller Direktur**

#### **DirectorDashboardController (app/Http/Controllers/Director/DashboardController.php)**
```php
<?php
namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(Request $request)
    {
        // 1. Hitung Total Cabang
        $totalBranches = Branch::count();

        // 2. Hitung Net Revenue Semua Cabang
        $totalNetRevenue = 0;
        $totalGrossRevenue = 0;
        $totalReturns = 0;

        $branches = Branch::all();
        foreach ($branches as $branch) {
            $branchRevenue = $this->financialService->calculateNetRevenue($branch->id);
            $totalGrossRevenue += $branchRevenue['total_sales'];
            $totalReturns += $branchRevenue['total_returns'];
            $totalNetRevenue += $branchRevenue['net_revenue'];
        }

        // 3. Transaksi Terbaru Semua Cabang
        $recentTransactions = Transaction::with('branch')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // 4. Top Performing Employees
        $topEmployees = User::where('role', 'employee')
            ->withCount(['transactions as transaction_count' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }])
            ->withSum(['transactions as total_sales' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }], 'total_amount')
            ->with('branch')
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return view('director.dashboard', compact(
            'totalBranches',
            'totalNetRevenue',
            'totalGrossRevenue',
            'totalReturns',
            'recentTransactions',
            'branches',
            'topEmployees'
        ));
    }
}
```

### **Dashboard Controller Manajer**

#### **ManagerDashboardController (app/Http/Controllers/Manager/DashboardController.php)**
```php
<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ReturnTransaction;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(Request $request)
    {
        $user = Auth::user()->load('branch');
        $branch = $user->branch;

        // 1. Financial Data Hari Ini
        $todayFinancial = $this->financialService->calculateNetRevenue(
            $branch->id,
            now()->startOfDay(),
            now()->endOfDay()
        );
        $todaySales = $todayFinancial['net_revenue'];
        $todayGrossSales = $todayFinancial['total_sales'];
        $todayReturns = $todayFinancial['total_returns'];

        // 2. Financial Data Bulan Ini
        $monthFinancial = $this->financialService->calculateNetRevenue(
            $branch->id,
            now()->startOfMonth(),
            now()->endOfMonth()
        );
        $monthSales = $monthFinancial['net_revenue'];

        // 3. Data Produk
        $totalProducts = Product::where('branch_id', $branch->id)->count();
        $activeProducts = Product::where('branch_id', $branch->id)
            ->where('is_active', true)
            ->count();

        // 4. Data Pegawai
        $totalEmployees = User::where('branch_id', $branch->id)
            ->where('role', 'employee')
            ->count();

        // 5. Transaksi Terbaru
        $recentTransactions = Transaction::where('branch_id', $branch->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // 6. Pending Returns
        $pendingReturns = ReturnTransaction::where('branch_id', $branch->id)
            ->where('status', 'menunggu')
            ->count();

        // 7. Top Employees Cabang
        $topEmployees = User::where('branch_id', $branch->id)
            ->where('role', 'employee')
            ->withCount(['transactions as transaction_count' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }])
            ->withSum(['transactions as total_sales' => function($query) {
                $query->where('status', 'completed')
                      ->whereMonth('created_at', now()->month);
            }], 'total_amount')
            ->orderBy('total_sales', 'desc')
            ->take(3)
            ->get();

        return view('manager.dashboard', compact(
            'todaySales',
            'todayGrossSales',
            'todayReturns',
            'monthSales',
            'totalProducts',
            'activeProducts',
            'totalEmployees',
            'recentTransactions',
            'pendingReturns',
            'topEmployees'
        ));
    }
}
```

### **Dashboard Controller Pegawai**

#### **EmployeeDashboardController (app/Http/Controllers/Employee/DashboardController.php)**
```php
<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Penjualan Hari Ini
        $todaySales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total_amount');

        // 2. Penjualan Bulan Ini
        $monthSales = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // 3. Total Transaksi
        $totalTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // 4. Transaksi Hari Ini
        $todayTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();

        // 5. Transaksi Terbaru
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        // 6. Info Cabang
        $branchInfo = $user->branch;

        return view('employee.dashboard', compact(
            'todaySales',
            'monthSales',
            'totalTransactions',
            'todayTransactions',
            'recentTransactions',
            'branchInfo'
        ));
    }
}
```

---

## 🎨 **3. View Layer**

### **Dashboard View Direktur**

#### **Director Dashboard (resources/views/director/dashboard.blade.php)**
```blade
@extends('layouts.app')

@section('title', 'Dashboard Direktur')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Dashboard Direktur</h1>
            <p>Ringkasan kinerja seluruh cabang toko</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">Direktur</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Total Cabang -->
            <div class="stat-card">
                <div class="stat-icon branches">
                    <i class="fas fa-store"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalBranches ?? 0 }}</div>
                    <div class="stat-label">Total Cabang</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Aktif
                    </div>
                </div>
            </div>

            <!-- Pendapatan Bersih -->
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalNetRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Bersih</div>
                    <div class="stat-change">
                        <i class="fas fa-calculator"></i> Kotor - Retur
                    </div>
                </div>
            </div>

            <!-- Pendapatan Kotor -->
            <div class="stat-card">
                <div class="stat-icon gross">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($totalGrossRevenue ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Kotor</div>
                    <div class="stat-change">
                        <i class="fas fa-chart-bar"></i> Total Penjualan
                    </div>
                </div>
            </div>

            <!-- Performance per Cabang -->
            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format(($totalNetRevenue ?? 0) / max($totalBranches ?? 1, 1), 0, ',', '.') }}</div>
                    <div class="stat-label">Rata-rata Bersih per Cabang</div>
                    <div class="stat-change">
                        <i class="fas fa-calculator"></i> Performance
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Employees Section -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">🏆 Pegawai Terbaik Bulan Ini</h2>
            </div>
            <div class="card-body">
                @if($topEmployees->count() > 0)
                <div class="employees-grid">
                    @foreach($topEmployees as $index => $employee)
                    <div class="employee-card rank-{{ $index + 1 }}">
                        <div class="employee-rank">
                            @if($index === 0)
                                <i class="fas fa-crown"></i>
                            @elseif($index === 1)
                                <i class="fas fa-medal"></i>
                            @else
                                <i class="fas fa-award"></i>
                            @endif
                            <span class="rank-number">#{{ $index + 1 }}</span>
                        </div>
                        <div class="employee-info">
                            <div class="employee-avatar">
                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $employee->name }}</div>
                                <div class="employee-branch">{{ $employee->branch->name ?? 'N/A' }}</div>
                                <div class="employee-stats">
                                    <span class="sales">Rp {{ number_format($employee->total_sales ?? 0, 0, ',', '.') }}</span>
                                    <span class="transactions">{{ $employee->transaction_count ?? 0 }} transaksi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada data pegawai bulan ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
```

### **Dashboard View Manajer**

#### **Manager Dashboard (resources/views/manager/dashboard.blade.php)**
```blade
@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Beranda Manajer</h1>
            <p>Ringkasan kinerja cabang toko Anda</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">Manajer</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Pendapatan Bersih -->
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                    <div class="stat-label">Pendapatan Bersih Hari Ini</div>
                    <div class="stat-change">
                        @if($todayReturns > 0)
                            <i class="fas fa-arrow-down"></i> Setelah dikurangi retur
                        @else
                            <i class="fas fa-info-circle"></i> Belum ada retur hari ini
                        @endif
                    </div>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Produk</div>
                    <div class="stat-change">
                        <i class="fas fa-check-circle"></i> {{ $activeProducts }} Aktif
                    </div>
                </div>
            </div>

            <!-- Total Pegawai -->
            <div class="stat-card">
                <div class="stat-icon employees">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalEmployees }}</div>
                    <div class="stat-label">Total Pegawai</div>
                    <div class="stat-change">
                        <i class="fas fa-user-check"></i> Aktif
                    </div>
                </div>
            </div>

            <!-- Pending Returns -->
            <div class="stat-card">
                <div class="stat-icon returns">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $pendingReturns }}</div>
                    <div class="stat-label">Retur Menunggu</div>
                    <div class="stat-change">
                        <i class="fas fa-clock"></i> Perlu Approval
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="heading-secondary">Aksi Cepat</h2>
            <div class="action-grid">
                <a href="{{ route('manager.products.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Produk</div>
                        <div class="action-desc">Manajemen stok dan produk</div>
                    </div>
                </a>

                <a href="{{ route('manager.employees.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Pegawai</div>
                        <div class="action-desc">Manajemen pegawai cabang</div>
                    </div>
                </a>

                <a href="{{ route('manager.returns.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kelola Retur</div>
                        <div class="action-desc">Approval retur pelanggan</div>
                    </div>
                </a>

                <a href="{{ route('manager.reports.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Laporan</div>
                        <div class="action-desc">Analisis penjualan cabang</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Top Employees Section -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">🏆 Pegawai Terbaik Bulan Ini</h2>
            </div>
            <div class="card-body">
                @if($topEmployees->count() > 0)
                <div class="employees-grid">
                    @foreach($topEmployees as $index => $employee)
                    <div class="employee-card rank-{{ $index + 1 }}">
                        <div class="employee-rank">
                            @if($index === 0)
                                <i class="fas fa-crown"></i>
                            @elseif($index === 1)
                                <i class="fas fa-medal"></i>
                            @else
                                <i class="fas fa-award"></i>
                            @endif
                            <span class="rank-number">#{{ $index + 1 }}</span>
                        </div>
                        <div class="employee-info">
                            <div class="employee-avatar">
                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                            </div>
                            <div class="employee-details">
                                <div class="employee-name">{{ $employee->name }}</div>
                                <div class="employee-stats">
                                    <span class="sales">Rp {{ number_format($employee->total_sales ?? 0, 0, ',', '.') }}</span>
                                    <span class="transactions">{{ $employee->transaction_count ?? 0 }} transaksi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada data pegawai bulan ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
```

### **Dashboard View Pegawai**

#### **Employee Dashboard (resources/views/employee/dashboard.blade.php)**
```blade
@extends('layouts.app')

@section('content')
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="page-title">
            <h1>Beranda Pegawai</h1>
            <p>Ringkasan aktivitas dan kinerja Anda</p>
        </div>
        <div class="user-info">
            <div class="user-details">
                <div class="name">{{ Auth::user()->name }}</div>
                <div class="role">Pegawai</div>
            </div>
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Penjualan Hari Ini -->
            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Hari Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-clock"></i> {{ date('d M Y') }}
                    </div>
                </div>
            </div>

            <!-- Penjualan Bulan Ini -->
            <div class="stat-card">
                <div class="stat-icon month">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Rp {{ number_format($monthSales, 0, ',', '.') }}</div>
                    <div class="stat-label">Penjualan Bulan Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-calendar"></i> {{ date('M Y') }}
                    </div>
                </div>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="stat-card">
                <div class="stat-icon transactions">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $todayTransactions }}</div>
                    <div class="stat-label">Transaksi Hari Ini</div>
                    <div class="stat-change">
                        <i class="fas fa-shopping-cart"></i> Selesai
                    </div>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalTransactions }}</div>
                    <div class="stat-label">Total Transaksi</div>
                    <div class="stat-change">
                        <i class="fas fa-history"></i> Semua Waktu
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch Info -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">📍 Informasi Cabang</h2>
            </div>
            <div class="card-body">
                <div class="branch-info">
                    <div class="branch-details">
                        <h3>{{ $branchInfo->name ?? 'N/A' }}</h3>
                        <p class="branch-address">{{ $branchInfo->address ?? 'Alamat tidak tersedia' }}</p>
                        <p class="branch-area">Area Operasional: {{ $branchInfo->operational_area ?? 'N/A' }}</p>
                        <div class="branch-contact">
                            <span><i class="fas fa-phone"></i> {{ $branchInfo->phone ?? 'N/A' }}</span>
                            <span><i class="fas fa-envelope"></i> {{ $branchInfo->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <h2 class="heading-secondary">📋 Transaksi Terbaru</h2>
            </div>
            <div class="card-body">
                @if($recentTransactions->count() > 0)
                <div class="transactions-list">
                    @foreach($recentTransactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-id">#{{ $transaction->invoice_number }}</div>
                            <div class="transaction-customer">{{ $transaction->customer_name }}</div>
                            <div class="transaction-date">{{ $transaction->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="transaction-amount">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </div>
                        <div class="transaction-method">
                            <span class="payment-badge {{ $transaction->payment_method }}">
                                {{ ucfirst($transaction->payment_method) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <p>Belum ada transaksi</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="heading-secondary">Aksi Cepat</h2>
            <div class="action-grid">
                <a href="{{ route('employee.transactions.create') }}" class="action-card primary">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Transaksi Baru</div>
                        <div class="action-desc">Buat transaksi penjualan</div>
                    </div>
                </a>

                <a href="{{ route('employee.transactions.index') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Riwayat Transaksi</div>
                        <div class="action-desc">Lihat semua transaksi</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
```

---

## 🔧 **4. Service Layer**

### **Financial Service (app/Services/FinancialService.php)**
```php
<?php
namespace App\Services;

use App\Models\Transaction;
use App\Models\ReturnTransaction;
use Carbon\Carbon;

class FinancialService
{
    /**
     * Calculate net revenue (gross sales - approved returns)
     */
    public function calculateNetRevenue($branchId, $startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? Carbon::now()->startOfMonth();
        $endDate = $endDate ?? Carbon::now()->endOfMonth();

        // Total Sales (Gross Revenue)
        $totalSales = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Total Approved Returns
        $totalReturns = ReturnTransaction::where('branch_id', $branchId)
            ->where('status', 'disetujui')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        // Net Revenue = Gross Sales - Returns
        $netRevenue = $totalSales - $totalReturns;

        return [
            'total_sales' => $totalSales,
            'total_returns' => $totalReturns,
            'net_revenue' => $netRevenue,
            'return_rate' => $totalSales > 0 ? ($totalReturns / $totalSales) * 100 : 0
        ];
    }
}
```

---

## 🎯 **5. Key Features Dashboard**

### **Fitur Utama Dashboard:**

#### **Dashboard Direktur:**
- ✅ **Multi-Branch Overview**: Data semua cabang
- ✅ **Net Revenue Calculation**: Pendapatan bersih vs kotor
- ✅ **Top Employees**: Pegawai terbaik lintas cabang
- ✅ **Branch Performance**: Rata-rata performance per cabang

#### **Dashboard Manajer:**
- ✅ **Branch-Specific Data**: Data khusus cabang yang dikelola
- ✅ **Financial Metrics**: Pendapatan harian, bulanan
- ✅ **Product & Employee Stats**: Jumlah produk dan pegawai
- ✅ **Pending Returns**: Retur yang menunggu approval
- ✅ **Quick Actions**: Akses cepat ke fitur utama

#### **Dashboard Pegawai:**
- ✅ **Personal Performance**: Penjualan personal harian/bulanan
- ✅ **Transaction History**: Riwayat transaksi personal
- ✅ **Branch Information**: Info cabang tempat bekerja
- ✅ **Quick Transaction**: Akses cepat buat transaksi baru

### **Business Logic Penting:**
- **Net Revenue**: Gross Sales - Approved Returns
- **Role-Based Data**: Setiap role hanya melihat data sesuai kewenangan
- **Real-time Calculation**: Data dihitung real-time saat dashboard diakses
- **Performance Tracking**: Tracking performance pegawai dan cabang
