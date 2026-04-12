@extends('layouts.admin')

@section('page-title')
Purchase Invoice Search
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Purchase Invoice Search</h2>
            </div>
        </div>

        <div class="x_content p-3">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form action="{{ route('purchase.invoice.searched') }}" method="get">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="purchase-invoices-search-area">
                                    <label class="py-1 border" for="purchase_invoices_search">Purchase Invoice No</label>
                                    <div class="form-group">
                                        <input type="number" name="purchase_invoices_no" class="form-control" value="{{ $invoiceNumber ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button pt-4">
                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <a href="{{ route('purchase.invoice.search') }}" class="btn btn-danger">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (!empty($invoiceNumber) && isset($supplier_info))

            <div class="row mt-4">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title p-3">
                            <div class="header-title d-flex justify-content-between gap-2">
                                <h2>Invoice Details</h2>
                                <a href="{{ route('purchase.print', $supplier_info->id)}}"
                                   onclick="MyWindow=window.open('{{ route('purchase.print', $supplier_info->id)}}','MyWindow','width=900,height=600'); return false;"
                                   class="btn btn-primary btn-sm p-2">
                                    Print <i class="fa fa-print text-white"></i>
                                </a>
                            </div>
                        </div>

                        <div class="x_content p-3" style="max-width: 800px; margin: 0 auto; float: unset;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h2 class="text-center text-dark">Invoice #{{ $supplier_info->id }}</h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                                    <div class="product-list-area">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Supplier Name</th>
                                                    <th>Address</th>
                                                    <th>Phone</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($supplier_info->date)) }}</td>
                                                    <td>{{ $supplier_info->supplier->company_name ?? 'N/A' }}</td>
                                                    <td>{{ $supplier_info->supplier->address ?? 'N/A' }}</td>
                                                    <td>{{ $supplier_info->supplier->mobile ?? 'N/A' }}</td>
                                                </tr>
                                            </tbody>
                                            <thead>
                                                <tr>
                                                    <th>Warehouse</th>
                                                    <th>Gari Number</th>
                                                    <th>Delivery Men</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $supplier_info->warehouse->name ?? 'N/A' }}</td>
                                                    <td>{{ $supplier_info->transport_no }}</td>
                                                    <td>{{ $supplier_info->delivery_man }}</td>
                                                    <td>{{ $supplier_info->supplier_remarks }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                                    <div class="product-list-area">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th class="text-center">Purchase(Qty)</th>
                                                    @if($supplier_info->product_discount > 0)
                                                        <th class="text-center">Dis.(Qty)</th>
                                                    @endif
                                                    <th class="text-center">Total Qty</th>
                                                    <th class="text-right">Price</th>
                                                    <th class="text-right">Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $product)
                                                    <tr>
                                                        <td class="text-center p-1">{{ $product->product->barcode ?? $product->product_code }}</td>
                                                        <td class="text-left p-1">{{ $product->product_name }}</td>
                                                        <td class="text-center p-1">
                                                            {{ $product->quantity - $product->discount_qty }}
                                                            {{ $product->product->type }}
                                                        </td>
                                                        @if($supplier_info->product_discount > 0)
                                                            <td class="text-center p-1">
                                                                {{ $product->discount_qty }}
                                                                {{ $product->product->type }}
                                                            </td>
                                                        @endif
                                                        <td class="text-center p-1">
                                                            {{ $product->quantity }}
                                                            {{ $product->product->type }}
                                                        </td>
                                                        <td class="text-right p-1">{{ formatAmount($product->unit_price) }}/=</td>
                                                        <td class="text-right p-1">{{ formatAmount($product->total_price) }}/=</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center p-3">Not Found!</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="calculation-area d-flex justify-content-end">
                                        @php
                                            $total_tk = $supplier_info->total_price - $supplier_info->price_discount;
                                            $gTotal = $supplier_info->total_price - $supplier_info->price_discount - $supplier_info->vat - $supplier_info->carring - $supplier_info->other_charge;
                                            $prev_balance = $supplier_info->balance != 0 ? $supplier_info->balance + $supplier_info->payment - $gTotal : 0;
                                        @endphp
                                        <table class="calculation_below_table">
                                            <tr>
                                                <th class="text-left pr-4">Total Price</th>
                                                <td class="text-right">{{ formatAmount($supplier_info->total_price) }}/=</td>
                                            </tr>
                                            @if($supplier_info->price_discount > 0)
                                                <tr>
                                                    <th class="text-left pr-4">Discount</th>
                                                    <td class="text-right">{{ formatAmount($supplier_info->price_discount ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th class="text-left pr-4">Total Tk</th>
                                                <td class="text-right"><b>{{ formatAmount($total_tk) }}/=</b></td>
                                            </tr>
                                            @if (abs($prev_balance) > 0)
                                                <tr>
                                                    <th class="text-left pr-4">Previous Due</th>
                                                    <td class="text-right">{{ formatAmount($prev_balance ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th class="text-left pr-4">Current Due</th>
                                                <td class="text-right"><b>{{ formatAmount($prev_balance + $total_tk) }}/=</b></td>
                                            </tr>
                                            @if(abs($supplier_info->vat) > 0)
                                                <tr>
                                                    <th class="text-left pr-4">VAT</th>
                                                    <td class="text-right">{{ formatAmount($supplier_info->vat ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            @if(abs($supplier_info->carring) > 0)
                                                <tr>
                                                    <th class="text-left pr-4">Carring</th>
                                                    <td class="text-right">{{ formatAmount($supplier_info->carring ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            @if(abs($supplier_info->other_charge) > 0)
                                                <tr>
                                                    <th class="text-left pr-4">Others</th>
                                                    <td class="text-right">{{ formatAmount($supplier_info->other_charge ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            @if (abs($supplier_info->payment) > 0)
                                                <tr>
                                                    <th class="text-left pr-4">Payment</th>
                                                    <td class="text-right">{{ formatAmount($supplier_info->payment ?? 0) }}/=</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th class="text-left pr-4">Total Payment</th>
                                                <td class="text-right">{{ formatAmount($supplier_info->payment + $supplier_info->other_charge + $supplier_info->carring + $supplier_info->vat) }}/=</td>
                                            </tr>
                                            <tr class="border-top">
                                                <th class="text-left pr-4">Balance</th>
                                                <td class="text-right"><b>{{ formatAmount($total = $supplier_info->balance) }}/=</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="in-word-area py-2 border-top border-bottom">
                                        <h4 class="text-left text-dark">In Words: {{ numberToWords($total) }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                                    <div class="border-top pt-2 mt-4" style="width: 150px; margin: 0 auto;">
                                        <h5 class="text-dark">Supplier Signature</h5>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                                    <div class="border-top pt-2 mt-4" style="width: 150px; margin: 0 auto;">
                                        <h5 class="text-dark">Authorized Signature</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @elseif(!empty($invoiceNumber))
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <div class="alert alert-warning">No Purchase Invoice found with ID: {{ $invoiceNumber }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
