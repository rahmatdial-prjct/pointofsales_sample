@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manajer Keuangan Report</h1>

    <form method="GET" action="{{ route('manager.reports.keuangan') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    @if($transactions->count())
    <canvas id="incomeChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('incomeChart').getContext('2d');

            var incomeData = {};
            @foreach ($transactions as $transaction)
                var date = '{{ $transaction->created_at->format('Y-m-d') }}';
                if (!incomeData[date]) {
                    incomeData[date] = 0;
                }
                incomeData[date] += {{ $transaction->total_amount }};
            @endforeach

            var labels = Object.keys(incomeData);
            var data = Object.values(incomeData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Bersih',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
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
    @endif

    <div class="financial-summary">
        <div class="summary-card">
            <h3>Ringkasan Keuangan</h3>
            <div class="summary-item total">
                <span class="label">Pendapatan Bersih:</span>
                <span class="value">Rp {{ number_format($financialData['net_revenue'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Penjualan Kotor:</span>
                <span class="value">Rp {{ number_format($financialData['total_sales'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Total Retur Diterima:</span>
                <span class="value negative">Rp {{ number_format($financialData['total_returns'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span class="label">Tingkat Retur:</span>
                <span class="value">{{ number_format($financialData['return_rate'] ?? 0, 2) }}%</span>
            </div>
        </div>
    </div>

    <style>
        .financial-summary {
            margin-top: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .summary-card h3 {
            margin-bottom: 1.5rem;
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item.total {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            margin: 1rem -2rem -2rem -2rem;
            padding: 1.5rem 2rem;
            border-radius: 0 0 12px 12px;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .label {
            color: #6b7280;
            font-weight: 500;
        }

        .value {
            color: #059669;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .value.negative {
            color: #dc2626;
        }

        .summary-item.total .label,
        .summary-item.total .value {
            color: #1f2937;
        }
    </style>
</div>
@endsection
