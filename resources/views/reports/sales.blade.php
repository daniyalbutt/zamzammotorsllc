@extends('layouts.app')

@section('title', 'Sales Reports')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-graph-up"></i> Sales Reports</h2>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.sales') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Sales</h6>
                        <h3 class="card-title mb-0">{{ $stats['total_sales'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-cart-check-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                        <h3 class="card-title mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Pending Invoices</h6>
                        <h3 class="card-title mb-0">{{ $stats['pending_invoices'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Vehicles Sold</h6>
                        <h3 class="card-title mb-0">{{ $stats['vehicles_sold'] }}</h3>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-car-front-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales by Agent -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-bar-chart-fill"></i> Sales by Agent</h5>
    </div>
    <div class="card-body">
        @if($sales_by_agent->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Agent Name</th>
                            <th>Sales Count</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales_by_agent as $agentName => $data)
                        <tr>
                            <td><i class="bi bi-person-circle"></i> {{ $agentName }}</td>
                            <td><span class="badge bg-success">{{ $data['count'] }}</span></td>
                            <td class="fw-bold">${{ number_format($data['revenue'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total:</td>
                            <td>{{ $sales_by_agent->sum('count') }}</td>
                            <td>${{ number_format($sales_by_agent->sum('revenue'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> No sales data available for the selected period.
            </div>
        @endif
    </div>
</div>
@endsection
