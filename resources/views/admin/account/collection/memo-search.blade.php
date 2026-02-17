@extends('layouts.admin')

@section('page-title')
Collection Memo Search
@endsection

@section('main-content')


<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Collection Memo Search</h2>
            </div>
        </div>

        <div class="x_content">
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
                    <form action="{{ route('collection.memo.searched') }}" method="get">
                        @csrf
                        <div class="row justify-content-center" style="max-width: 720px; margin: 0 auto; float: unset;">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <label  class="py-1 border" for="collection_memo_no">Collection Memo No</label>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <div class="form-group">
                                    <input type="text" name="collection_memo_no" value="{{$memoNumber ?? ''}}" id="collection_memo_no" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="customer-search-button">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-sm">Search</button>
                                        <button type="button" class="btn btn-danger btn-sm">Reset</button>
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
            {{-- @dd($collectionMemo) --}}
            @if (!empty($collectionMemo) && isset($collectionMemo))

                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="x_panel">
                            <div class="x_title text-right relative">
                                <a href="#" onClick="MyWindow=window.open('{{route('collection.print', $collectionMemo->id )}}','Collection Memo Print','width=900,height=600'); return false;"
                                    class="btn btn-primary btn-sm absolute right-0 top-0"><i class="fa fa-print text-white"></i> Print</a>
                            </div>

                            <div class="" style="max-width: 720px; margin: 0 auto; float: unset;">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <h2 class="text-center text-dark">Collection Memo No# {{ $collectionMemo->id}}</h2>
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
                                                            {{ $collectionMemo->customer->name }}</td>
                                                        <td class="text-center">
                                                            {{ $collectionMemo->customer->address }} </td>
                                                        <td>{{ $collectionMemo->customer->mobile }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Received By</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center text-nowrap">
                                                            {{ date('d-m-Y', strtotime($collectionMemo->date)) }}</td>
                                                        <td class="text-center">{{ $collectionMemo->payment_by }}</td>
                                                        <td class="text-center">{{ $collectionMemo->collection_remarks }}</td>
                                                    </tr>

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="calculation-area d-flex justify-content-end">
                                            <table class="calculation_below_table">

                                                <tr>
                                                    <th>Previous Due</th>
                                                    <td>{{ $collectionMemo->balance + $collectionMemo->payment }}/=</td>
                                                </tr>

                                                @if (empty($collectionMemo->payment))
                                                    <tr></tr>
                                                @else
                                                    <tr>
                                                        <th>Collection Amount</th>
                                                        <td> {{ $collectionMemo->payment }}/=</td>
                                                    </tr>
                                                @endif

                                                <tr>
                                                    <th>Current Due</th>
                                                    <td> {{ $collectionMemo->balance }}/=</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="in-word-area">
                                            <h4 class="text-left text-dark">In Words: {{ numberToWords($collectionMemo->balance) }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="bottom-area row">
                                            <div class="col-lg-4 col-md-4 col-sm-6 mr-auto">
                                                <div class="customer-signature-area">
                                                    <h4 class="text-center text-dark">Customer Signature</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-6">
                                                <div class="customer-signature-area">
                                                    <h4 class="text-center text-dark">Authorised Signature</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="thanks-area">
                                            <h5 class="text-center text-dark"><small>Thank you. Please come again</small></h5>
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
                        <h4 class="text-center">No Collection Memo Found</h4>
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
