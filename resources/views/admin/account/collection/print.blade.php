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

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="banner-area py-3">
                                    <img src="{{ asset('assets/images/firoz_header.jpg') }}" width="100%" height="120"
                                        alt="">
                                </div>
                                <h5 class="text-center text-dark collection_print_title">Challan# {{$invoice->id}} </h5>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="product-list-area">

                                    <table class="table table-bordered table-striped table-sm collection_print_table">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Phone</th>

                                            </tr>
                                            <tr>
                                                <td class="text-center  memo_product_title">
                                                    {{ $invoice->customer->name }}
                                                </td>
                                                <td class="text-center comon_column">
                                                    {{ $invoice->customer->address }}
                                                </td>
                                                <td class="text-center text-nowrap comon_column">
                                                    {{ $invoice->customer->mobile  }}
                                                </td>


                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Paying By</th>
                                                <th>Remarks</th>

                                            </tr>
                                            <tr>
                                                <td class="text-center comon_column">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                                <td class="text-center comon_column"> {{ $invoice->payment_by }} {{$invoice->payment_by == 'Bank' ? '(<strong>:</strong>)' : ''}} {{ $invoice->bank_title }}</td>
                                                <td class="text-center comon_column">{{ $invoice->received_by }}</td>

                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="calculation-area d-flex justify-content-end">
                                    <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                                    @php
                                        $prev_due = $invoice->balance + $invoice->payment;
                                    @endphp
                                    <table class="calculation_below_table">
                                        <tr>
                                            <th>Previous Due</th>
                                            <td>{{ $prev_due }}/=</td>
                                        </tr>

                                        @if (empty($invoice->payment))
                                            <tr></tr>
                                        @else
                                            <tr>
                                                <th>Payment</th>
                                                <td><strong>{{ $invoice->payment }}/=</strong></td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>Current Balance</th>
                                            <td> {{ $invoice->balance }}/=</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="in-word-area">
                                    <h4 class="text-left text-dark">In Words:
                                        <span>{{ numberToWords($invoice->payment) }}</span>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 pt-5">
                                <div class="row ">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="customer-signature-area collection_print_signature">
                                            <h4 class="text-center text-dark">Customer Signature</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="thanks-area">
                                            <h5 class="text-center text-dark"></h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="supplier-signature-area collection_print_signature">
                                            <h4 class="text-center text-dark">Authorized Signature</h4>
                                        </div>
                                </div>
                            </div>
                            {{-- invoice section end here --}}

                            <div class="col-lg-12 col-md-12 col-sm-12 print-button">
                                <a href="#" onClick="window.print()">Print <i class="fa fa-print text-danger"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-------end print row ---}}




    </div>
@endsection
