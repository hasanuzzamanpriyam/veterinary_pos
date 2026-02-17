@extends('layouts.admin')

@section('page-title')
Payment View
@endsection

@section('main-content')

<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2 class="mr-auto">Checkout</h2>
                <a href="#" onClick="MyWindow=window.open('{{route('payment.print', $supplier_info->id )}}','MyWindow','width=900,height=600'); return false;"
                    class="btn btn-primary btn-sm"><i class="fa fa-print text-white"></i> Print</a>
                    <a href="{{ route('payment.index') }}" class="ml-3 cursor-pointer btn btn-primary btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a>
            </div>
        </div>

        <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="row">
                <div class="col-lg-12 col-md-21 col-sm-12">
                    <h2 class="text-center text-dark">Cash Memo# {{ $supplier_info->id }}</h2>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="customer_area">
                        <table class="table table-bordered table-striped table-sm">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>

                                </tr>
                                <tr>
                                    <td class="text-center  memo_product_title">
                                        {{ $supplier_info->supplier->name }} {{ $supplier_info->supplier->company_name }}</td>
                                    <td class="text-center comon_column">
                                        {{ $supplier_info->supplier->address }} </td>
                                    <td class="text-center comon_column">{{ $supplier_info->supplier->mobile  }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <th>Paying By</th>
                                    <th>Remarks</th>

                                </tr>
                                <tr>
                                    <td class="text-center text-nowrap comon_column">
                                        {{ date('d-m-Y', strtotime($supplier_info->date)) }}</td>
                                    <td class="text-center comon_column">{{ $supplier_info->payment_by }}</td>
                                    <td class="text-center comon_column">{{ $supplier_info->payment_remarks }}</td>

                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-lg-12 col-md-21 col-sm-12">
                    <div class="calculation-area d-flex justify-content-end">
                        <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                        <table class="calculation_below_table">
                            <tr>
                                <th>Previous Due</th>
                                <td>{{ formatAmount($supplier_info->balance + $supplier_info->payment) }}/=</td>
                            </tr>
                            <tr>
                                <th>Paid Amount</th>
                                <td> {{ formatAmount($supplier_info->payment) }}/=</td>
                            </tr>

                            <tr>
                                <th>Current Due</th>
                                <td> {{ formatAmount($supplier_info->balance) }}/=</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-lg-12 col-md-21 col-sm-12">
                    <div class="in-word-area">
                        <h4 class="text-left text-dark">In Words:
                            <span>{{ numberToWords($supplier_info->balance) }}</span>
                        </h4>
                    </div>
                </div>

                <div class="col-lg-12 col-md-21 col-sm-12 pt-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="bottom-area py-3">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="customer-signature-area">
                                        <h4 class="text-center text-dark">Supplier Signature</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="thanks-area">
                                        <h5 class="text-center text-dark"></h5>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="supplier-signature-area">
                                        <h4 class="text-center text-dark">Authorized Signature</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- invoice section end here --}}
            </div>
        </div>

    </div>
</div>


@endsection
