@extends('layouts.admin')
@section('page-title')
    Dashboard
@endsection
@section('main-content')
    <div class="container-fluid px-4 dashboard-container" style="overflow-y: auto; max-height: calc(100vh - 120px);">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">Dashboard Overview</h1>
            <span class="badge bg-primary text-white px-3 py-2">{{ now()->format('l, d F Y') }}</span>
        </div>

        <!-- Today's Sales Section -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Today's Sales
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-info bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Invoices</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $totalInvoiceCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-file-invoice fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-warning bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Quantity</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $total_qty_sales_today ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-cubes fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-danger bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Weight (tons)</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format(($todaysTotalSellsWeight ?? 0) / 1000, 2) }}</h3>
                                </div>
                                <i class="fas fa-weight fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-success bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Revenue</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalSalesToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-primary bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Collection</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalSalesCollectionToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-hand-holding-usd fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-dark bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalDueToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-clock fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Purchase Section -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="fas fa-shopping-cart me-2 text-success"></i>Today's Purchase
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-warning bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Quantity</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $total_qty_purchase_today ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-boxes fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-danger bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Weight (tons)</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format(($todaysTotalPurchaseWeight ?? 0) / 1000, 2) }}</h3>
                                </div>
                                <i class="fas fa-weight-hanging fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-success bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Purchase</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalPurchaseToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-truck fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-primary bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Payment</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalPurchasePaymentToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-credit-card fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-dark bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalPurchaseDueToday ?? 0) }}/=</h3>
                                </div>
                                <i class="fas fa-hourglass-half fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Overview Section -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="fas fa-chart-pie me-2 text-info"></i>Overall Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-info bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Stock Qty</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $totalStockQuantity ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-archive fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-warning bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Stock Value</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalStockValue ?? 0, 2) }}/=</h3>
                                </div>
                                <i class="fas fa-coins fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-danger bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due Customers</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $totalDueCustomerCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-users fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-success bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due Amount (Cust.)</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalDueCustomerAmount ?? 0, 2) }}/=</h3>
                                </div>
                                <i class="fas fa-user-clock fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-primary bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due Suppliers</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ $totalDueSupplierCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-truck-moving fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6">
                        <div class="card bg-dark bg-gradient text-white h-100 border-0 shadow-sm rectangular-card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="text-white-50 mb-2 text-truncate">Due Amount (Sup.)</h6>
                                    <h3 class="fw-bold mb-0 text-wrap">{{ number_format($totalDueSupplierAmount ?? 0, 2) }}/=</h3>
                                </div>
                                <i class="fas fa-hand-holding-heart fa-2x opacity-50 align-self-end"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer note -->
        <div class="text-muted text-center mt-4 small">
            <i class="fas fa-sync-alt me-1"></i> Data updates in real-time
        </div>
    </div>
@endsection

@push('styles')
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Make cards rectangular */
        .rectangular-card {
            border-radius: 4px !important; /* small radius for a subtle rectangular look */
        }
        .rectangular-card .card-body {
            padding: 1rem;
        }
        /* Gradient background (kept) */
        .bg-gradient {
            background: linear-gradient(145deg, rgba(255,255,255,0.1) 0%, rgba(0,0,0,0.1) 100%);
        }
        /* Hover effect */
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
        }
        .text-white-50 {
            color: rgba(255,255,255,0.7) !important;
        }
        .opacity-50 {
            opacity: 0.5;
        }
        /* Ensure long numbers wrap */
        .text-wrap {
            word-wrap: break-word;
            white-space: normal;
        }
        /* Optional: scrollbar for dashboard container */
        .dashboard-container {
            scrollbar-width: thin;
            scrollbar-color: #c0c0c0 #f0f0f0;
        }
        .dashboard-container::-webkit-scrollbar {
            width: 6px;
        }
        .dashboard-container::-webkit-scrollbar-track {
            background: #f0f0f0;
        }
        .dashboard-container::-webkit-scrollbar-thumb {
            background-color: #c0c0c0;
            border-radius: 3px;
        }
    </style>
@endpush