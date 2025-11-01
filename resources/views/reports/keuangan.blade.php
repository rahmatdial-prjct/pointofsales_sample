@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $role === 'manager' ? 'Manajer' : 'Direktur' }} Keuangan Report</h1>

    @if($role === 'director')
    <form method="GET" action="{{ route('director.reports.keuangan') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="branch_id">Branch</label>
                <select id="branch_id" name="branch_id" class="form-control" onchange="this.form.submit()">
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == $branchId ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="group_by">Group By</label>
                <select id="group_by" name="group_by" class="form-control" onchange="this.form.submit()">
                    <option value="week" {{ $groupBy == 'week' ? 'selected' : '' }}>Week</option>
                    <option value="month" {{ $groupBy == 'month' ? 'selected' : '' }}>Month</option>
                    <option value="year" {{ $groupBy == 'year' ? 'selected' : '' }}>Year</option>
                    <option value="day" {{ $groupBy == 'day' ? 'selected' : '' }}>Day</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-md-2">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control" onchange="this.form.submit()">
            </div>
        </div>
    </form>
    @else
    <form method="GET" action="{{ route('manager.reports.keuangan') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control" onchange="this.form.submit()">
            </div>
        </div>
    </form>
    @endif

    <h2>Financial Performance</h2>

    <canvas id="financialPerformanceChart" width="800" height="400"></canvas>

    <p>Total Income: {{ number_format($totalIncome ?? 0, 2) }}</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('financialPerformanceChart').getContext('2d');

        var groupedTotals = @json($groupedTotals);
        var labels = Object.keys(groupedTotals);
        var data = Object.values(groupedTotals);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Income',
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
@endsection
