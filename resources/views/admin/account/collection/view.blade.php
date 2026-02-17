@extends('layouts.admin')

@section('page-title')
    Collection View
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Checkout</h2>
                <a href="{{ route('collection.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
                <a href="{{route('collection.print',$invoice->id )}}" onClick="MyWindow=window.open('{{route('collection.print',$invoice->id )}}','MyWindow','width=900,height=600'); return false;"
                        class="btn btn-primary btn-sm p-2">Print <i class="fa fa-print text-white"></i></a>
            </div>
        </div>
        <div class="x_content" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="text-center text-dark">Invoice: {{ $invoice->id }}</h2>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="product-list-area">
                        {{-- {{dump($invoice)}} --}}
                        <table class="table table-bordered table-striped table-sm">
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
                                    {{-- {{ dump($invoice)}} --}}

                                    <td class="text-center comon_column">{{ date('d-m-Y', strtotime($invoice->date)) }}</td>
                                    <td class="text-center comon_column"> {{ $invoice->payment_by }} {{$invoice->payment_by == 'Bank' ? ':' : ''}} {{ $invoice->bank_title }}</td>
                                    <td class="text-center comon_column">{{ $invoice->received_by }}</td>

                                </tr>

                            </tbody>

                        </table>
                    </div>
                </div>




                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="calculation-area d-flex justify-content-end">
                        <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                        {{-- @dump($invoice) --}}
                        @php
                            $prev_due = $invoice->balance + $invoice->payment;
                        @endphp
                        <table class="calculation_below_table">
                            <tr>
                                <th>Previous Due</th>
                                <td>{{ formatAmount($prev_due) }}/=</td>
                            </tr>

                            @if (empty($invoice->payment))
                                <tr></tr>
                            @else
                                <tr>
                                    <th>Collection</th>
                                    <td><strong>{{ formatAmount($invoice->payment) }}/=</strong></td>
                                </tr>
                            @endif

                            <tr>
                                <th>Current Balance</th>
                                <td> {{ formatAmount($invoice->balance) }}/=</td>
                            </tr>

                        </table>
                    </div>
                </div>







                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="in-word-area">
                        <h4 class="text-left text-dark">In Words:
                            <span> {{ numberToWords($invoice->balance) }}</span>
                        </h4>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 pt-5">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="bottom-area py-3">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="customer-signature-area">
                                        <h4 class="text-center text-dark">Customer Signature</h4>
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
