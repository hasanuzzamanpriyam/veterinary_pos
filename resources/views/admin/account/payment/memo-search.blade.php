@extends('layouts.admin')

@section('page-title')
Payment Memo Search
@endsection

@section('main-content')


<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Payment Memo Search</h2>
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
                    <form action="{{ route('payment.memo.searched') }}" method="get">
                        @csrf
                        <div class="row justify-content-center" style="max-width: 720px; margin: 0 auto; float: unset;">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <label  class="py-1 border" for="payment_memo_no">Payment Memo No</label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <div class="form-group">
                                    <input type="text" name="payment_memo_no" value="{{$memoNumber ?? ''}}" id="payment_memo_no" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Search</button>
                                        <button type="button" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                            {{-- invoice section goes here --}}
                    </div>
                </div>
            </div>
            {{-- @dd($paymentMemo) --}}
            @if (!empty($paymentMemo) && isset($paymentMemo))

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title p-3 text-right">
                                <a href="#" onClick="MyWindow=window.open('{{route('payment.print', $paymentMemo->id )}}','Payment Memo Print','width=900,height=600'); return false;"
                                    class="btn btn-primary btn-sm"><i class="fa fa-print text-white"></i> Print</a>
                            </div>

                            <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <h2 class="text-center text-dark">Payment Memo No# {{ $paymentMemo->id}}</h2>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                                        <div class="product-list-area">
                                            <table class="table table-bordered table-striped table-sm">
                                                <tbody>
                                                    <tr>

                                                        <th>Name</th>
                                                        <th>Address</th>
                                                        <th>Phone</th>
                                                    </tr>
                                                    <tr>


                                                        <td class="text-center">
                                                            {{ $paymentMemo->supplier->company_name }}</td>
                                                        <td class="text-center">
                                                            {{ $paymentMemo->supplier->address }} </td>
                                                        <td>{{ $paymentMemo->supplier->mobile }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Paying By</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center text-nowrap">
                                                            {{ date('d-m-Y', strtotime($paymentMemo->date)) }}</td>
                                                        <td class="text-center">{{ $paymentMemo->payment_by }}</td>
                                                        <td class="text-center">{{ $paymentMemo->payment_remarks }}</td>
                                                    </tr>

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="calculation-area d-flex justify-content-end">
                                            <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                                            <table class="calculation_below_table">

                                                <tr>
                                                    <th>Previous Due</th>
                                                    <td>{{ $paymentMemo->balance + $paymentMemo->payment }}/=</td>
                                                </tr>

                                                @if (empty($paymentMemo->payment))
                                                    <tr></tr>
                                                @else
                                                    <tr>
                                                        <th>Paid Amount</th>
                                                        <td> {{ $paymentMemo->payment }}/=</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>Current Due</th>
                                                    <td> {{ $paymentMemo->balance }}/=</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="in-word-area py-3">
                                            <h4 class="text-left text-dark">In Words: {{ numberToWords($paymentMemo->balance) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="bottom-area py-3">
                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                <div class="customer-signature-area">
                                                    <h4 class="text-left text-dark">Customer Signature</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                <div class="thanks-area">
                                                    <h5 class="text-center text-dark">Thanks will come again</h5>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                <div class="supplier-signature-area">
                                                    <h4 class="text-center text-dark">Supplier Signature</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else

                {{-- <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="alert alert-danger">
                        <h4 class="text-center">No Payment Memo Found</h4>
                    </div>
                </div> --}}

            @endif
        </div>
    </div>
</div>


@push('scripts')
<script>

    // $(document).ready(function () {



    // });
    </script>

@endpush



@endsection
