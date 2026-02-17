@extends('layouts.print')

@section('page-title')
    Collection View
@endsection

@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="x_panel">
                    <div class="invoice-print">
                        <div class="row">

                            <div class="col-lg-12 col-md-21 col-sm-12">
                                <div class="banner-area py-3">
                                    <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="120"
                                        alt="">
                                </div>
                                <h2 class="text-center text-dark payment_print_title">Cash Memo# {{ $invoice->id }}</h2>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="customer_area payment_print_table">
                                    <table class="table table-bordered table-striped table-sm">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Phone</th>

                                            </tr>
                                            <tr>
                                                <td class="text-center  memo_product_title">
                                                    {{ $invoice->supplier->name }} {{ $invoice->supplier->company_name }}</td>
                                                <td class="text-center comon_column">
                                                    {{ $invoice->supplier->address }} </td>
                                                <td class="text-center comon_column">{{ $invoice->supplier->mobile  }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Paying By</th>
                                                <th>Remarks</th>

                                            </tr>
                                            <tr>
                                                <td class="text-center text-nowrap comon_column">
                                                    {{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                                <td class="text-center comon_column">{{ $invoice->payment_by }}</td>
                                                <td class="text-center comon_column">{{ $invoice->payment_remarks }}</td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="col-lg-12 col-md-21 col-sm-12">
                                <div class="calculation-area d-flex justify-content-end">
                                    <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                                    <table class="calculation_below_table payment_print_cal_table">
                                        <tr>
                                            <th>Previous Due</th>
                                            <td>{{ $invoice->balance + $invoice->payment }}/=</td>
                                        </tr>

                                        @if (empty($invoice->payment))
                                            <tr></tr>
                                        @else
                                            <tr>
                                                <th>Paid Amount</th>
                                                <td> {{ $invoice->payment }}/=</td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>Current Due</th>
                                            <td> {{ $invoice->balance }}/=</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-21 col-sm-12">
                                <div class="in-word-area">
                                    <h4 class="text-left text-dark">In Words:
                                        <span>{{ numberToWords($invoice->balance ) }}</span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-21 col-sm-12 pt-5 ">
                                <table class="table ">
                                    <tbody>
                                        <tr>
                                            <td class="border-0" style="width: 40%">
                                                <div class="supplier-signature-area">
                                                    <h4 class="text-center text-dark">Supplier Signature
                                                    </h4>
                                                </div>
                                            </td>
                                            <td class="border-0" style="width: 20%">
                                            </td>
                                            <td class="border-0" style="width: 40%">
                                                <div class="supplier-signature-area">
                                                    <h4 class="text-center text-dark">Authorized Signature
                                                    </h4>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            {{-- invoice section end here --}}
                            <div class="col-lg-12 col-md-21 col-sm-12 print-button">
                                <a href="#" onClick="window.print()">Print <i class="fa fa-print text-danger"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
