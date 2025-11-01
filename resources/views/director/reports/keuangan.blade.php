@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="top-bar">
        <div class="page-title">
            <h1>Laporan Keuangan</h1>
            <p>Halaman laporan keuangan cabang toko</p>
        </div>
    </div>

    <form method="GET" action="{{ route('director.reports.keuangan') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label for="branch_id" class="block text-sm font-medium text-gray-700">Pilih Cabang</label>
            <select id="branch_id" name="branch_id" class="border rounded p-1" onchange="this.form.submit()">
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $reportData && $reportData['branch']->id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" id="start_date" name="start_date" value="{{ $reportData['startDate'] ?? '' }}" class="border rounded p-1">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
            <input type="date" id="end_date" name="end_date" value="{{ $reportData['endDate'] ?? '' }}" class="border rounded p-1">
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        </div>
    </form>

    @if ($reportData)
        <section class="branch-report my-6 p-4 border rounded bg-white shadow">
            <h2 class="text-xl font-semibold mb-4">Cabang: {{ $reportData['branch']->name }}</h2>

            <canvas id="financialChart_{{ $reportData['branch']->id }}" width="400" height="200"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var ctx = document.getElementById('financialChart_{{ $reportData['branch']->id }}').getContext('2d');

                    var dailyIncome = @json($reportData['dailyIncome']);

                    var labels = Object.keys(dailyIncome);
                    var data = Object.values(dailyIncome);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Pendapatan Harian',
                                data: data,
                                fill: false,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
            </script>

            <div class="summary-stats grid grid-cols-4 gap-4 mb-6">
                <div class="stat p-4 bg-green-100 rounded text-center shadow-md">
                    <h3 class="text-lg font-semibold text-green-800">Pendapatan Bersih</h3>
                    <p class="text-2xl text-green-600 font-bold">Rp {{ number_format($reportData['netRevenue'], 0, ',', '.') }}</p>
                    <small class="text-green-600">
                        @if($reportData['totalReturns'] > 0)
                            <i class="fas fa-arrow-down"></i> Setelah dikurangi retur
                        @else
                            <i class="fas fa-info-circle"></i> Belum ada retur
                        @endif
                    </small>
                </div>
                <div class="stat p-4 bg-blue-100 rounded text-center shadow-md">
                    <h3 class="text-lg font-semibold text-blue-800">Penjualan Kotor</h3>
                    <p class="text-2xl text-blue-600 font-bold">Rp {{ number_format($reportData['totalGrossIncome'], 0, ',', '.') }}</p>
                    <small class="text-blue-600">
                        @if($reportData['totalReturns'] > 0)
                            <i class="fas fa-arrow-up"></i> Sebelum dikurangi retur
                        @else
                            <i class="fas fa-info-circle"></i> Belum ada retur
                        @endif
                    </small>
                </div>
                <div class="stat p-4 bg-red-100 rounded text-center shadow-md">
                    <h3 class="text-lg font-semibold text-red-800">Total Retur</h3>
                    <p class="text-2xl text-red-600 font-bold">Rp {{ number_format($reportData['totalReturns'], 0, ',', '.') }}</p>
                    <small class="text-red-600">
                        <i class="fas fa-undo"></i> Retur yang disetujui
                    </small>
                </div>
                <div class="stat p-4 bg-yellow-100 rounded text-center shadow-md">
                    <h3 class="text-lg font-semibold text-yellow-800">Tingkat Retur</h3>
                    <p class="text-2xl text-yellow-600 font-bold">{{ number_format($reportData['returnRate'], 2) }}%</p>
                    <small class="text-yellow-600">
                        <i class="fas fa-percentage"></i> Dari total penjualan
                    </small>
                </div>
            </div>

            <div class="transaction-table overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID Transaksi</th>
                            <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                            <th class="border border-gray-300 px-4 py-2">Jumlah Total</th>
                            <th class="border border-gray-300 px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reportData['transactions'] as $transaction)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $transaction->id }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $transaction->created_at->format('d-m-Y') }}</td>
                                <td class="border border-gray-300 px-4 py-2">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $transaction->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">Tidak ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex gap-4">
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export PDF</button>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Export Excel</button>
            </div>
        </section>
    @endif
@endsection
