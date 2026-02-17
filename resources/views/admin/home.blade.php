@extends('layouts.admin')
@section('page-title')
    Welcome to Dashboard
@endsection
@section('main-content')

    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title">
                    <h2>Dashboard Overview</h2>
                </div>
            </div>
            <div class="x_content p-3">
                <div class="row">

                    <!-- First Row: Total Sales -->
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="daily-update-area">
                            <div class="header-title">
                                <h3 class="text-dark text-center">Total Sales on {{now()->format('d-F-Y')}}</h3>
                            </div>
                            <div class="row">
                                {{-- total invoice --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-info text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Invoice No.</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $totalInvoiceCount ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- total quantity --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-warning text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Quantity</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $total_qty_sales_today ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- total weight --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-danger text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Weight</h4>
                                        </div>
                                        <div class="items-description">
                                            @php
                                                $tons = ($todaysTotalSellsWeight ?? 0) / 1000;
                                            @endphp
                                            <h5 class="text-center">
                                                {{ number_format($tons, 2) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                {{-- total sale --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-success text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Revenue</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $totalSalesToday ?? 0 }}/=</h5>
                                        </div>
                                    </div>
                                </div>


                                {{-- Due Supplier --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-primary text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Collection</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{$totalSalesCollectionToday ?? 0}}/=</h5>
                                        </div>
                                    </div>
                                </div>
                                {{-- total due --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-dark text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Total Due</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{$totalDueToday ?? 0}}/=</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row: Two Columns (Purchase and Payment) -->
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="row">
                            <!-- Total Purchase Section -->
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="daily-update-area">
                                    <div class="header-title mb-3">
                                        <h3 class="text-dark text-center">Total Purchase on {{now()->format('d-F-Y')}}</h3>
                                    </div>
                                    <div class="row">

                                        <!-- Left Side: Title + 3 Items -->
                                        <div class="col-lg-7 col-md-7 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                                    <div class="single-item bg-warning text-light text-center py-3">
                                                        <div class="items-title">
                                                            <h4 class="text-center">Total Quantity</h4>
                                                        </div>
                                                        <div class="items-description">
                                                            <h5 class="text-center">{{ $total_qty_purchase_today ?? 0 }}</h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                                    <div class="single-item bg-danger text-light text-center py-3">
                                                        <div class="items-title">
                                                            <h4 class="text-center">Total Weight</h4>
                                                        </div>
                                                        <div class="items-description">
                                                            @php
                                                                $tons = ($todaysTotalPurchaseWeight ?? 0) / 1000;
                                                            @endphp
                                                            <h5 class="text-center">
                                                                {{ number_format($tons, 2) }}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                                                    <div class="single-item bg-success text-light text-center py-3">
                                                        <div class="items-title">
                                                            <h4 class="text-center">Total Purchase</h4>
                                                        </div>
                                                        <div class="items-description">
                                                            <h5 class="text-center">{{ $totalPurchaseToday ?? 0 }}/=</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Side: Payment + Due -->
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <div class="single-item bg-primary text-light text-center py-3">
                                                        <div class="items-title">
                                                            <h4 class="text-center">Total Payment</h4>
                                                        </div>
                                                        <div class="items-description">
                                                            <h5 class="text-center">{{ $totalPurchasePaymentToday ?? 0 }}/=
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                                    <div class="single-item bg-dark text-light text-center py-3">
                                                        <div class="items-title">
                                                            <h4 class="text-center">Total Due</h4>
                                                        </div>
                                                        <div class="items-description">
                                                            <h5 class="text-center">{{ $totalPurchaseDueToday ?? 0 }}/=</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4">
                        <div class="daily-update-area">
                            <div class="header-title">
                                <h3 class="text-dark text-center">Overview Details</h3>
                            </div>
                            <div class="row">
                                {{-- Stock Quantity --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-info text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Stock Quantity</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $totalStockQuantity ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- Stock Value --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-warning text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Stock Value</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ number_format($totalStockValue ?? 0, 2) }}/=</h5>
                                        </div>
                                    </div>
                                </div>

                                {{-- Due Customer --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-danger text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Due Customer</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $totalDueCustomerCount ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                                {{-- Due Cus.Amount --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-success text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Due Cus.Amount</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ number_format($totalDueCustomerAmount ?? 0, 2) }}/=
                                            </h5>
                                        </div>
                                    </div>
                                </div>


                                {{-- Due Supplier --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-primary text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Due Supplier</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ $totalDueSupplierCount ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                                {{-- Due Sup.Amount --}}
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <div class="single-item bg-dark text-light text-center py-3">
                                        <div class="items-title">
                                            <h4 class="text-center">Due Sup.Amount</h4>
                                        </div>
                                        <div class="items-description">
                                            <h5 class="text-center">{{ number_format($totalDueSupplierAmount ?? 0, 2) }}/=
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection