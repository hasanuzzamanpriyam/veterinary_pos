@extends('layouts.admin')

@section('page-title')
Sales Invoice Search
@endsection

@section('main-content')


<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Invoice Search</h2>

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
                    <form action="{{ route('sales.invoice.searched') }}" method="get">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <div class="sales-invoices-search-area">
                                    <label  class="py-1 border" for="sales_invoices_search">Sales Invoice No</label>
                                    <div class="form-group">
                                    <input type="number" name="sales_invoices_no"  class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="supplier-search-button pt-4">
                                    <div class="form-group pt-3">
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

            @if (!empty($invoiceNumber) && isset($invoiceNumber))

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title p-3">
                            <div class="header-title d-flex justify-content-between gap-2">
                                <h2></h2>

                                <a href="{{ route('sales.invoice', $customer_info->id) }}"
                                    onClick="MyWindow=window.open('{{ route('sales.invoice', $customer_info->id) }}','MyWindow','width=900,height=700'); return false;"
                                    class="btn btn-primary btn-sm p-2">
                                    Print <i class="fa fa-print text-white"></i>
                                </a>
                            </div>
                        </div>

                        <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h2 class="text-center text-dark">Challan# {{ $customer_info->id}}</h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                                    <div class="product-list-area">
                                        <table class="table table-bordered table-striped table-sm">
                                            <tbody>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Address</th>
                                                    <th>Phone</th>
                                                </tr>
                                                <tr>

                                                    <td class="text-center text-nowrap comon_column">
                                                        {{ date('d-m-Y', strtotime($customer_info->date)) }}</td>
                                                    <td class="text-center  memo_product_title">
                                                        {{ $customer_info->customer->name }}</td>
                                                    <td class="text-center comon_column">
                                                        {{ $customer_info->customer->address }} </td>
                                                    <td>{{$customer_info->customer->mobile}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Store Name</th>
                                                    <th>Gari No</th>
                                                    <th>Delivery Man</th>
                                                </tr>
                                                <tr>
                                                    <td class="text-center comon_column">{{ $customer_info->id }}</td>
                                                    <td class="text-center comon_column">{{ $customer_info->store->name }}
                                                    </td>
                                                    <td class="text-center comon_column">{{ $customer_info->transport_no }}
                                                    </td>
                                                    <td class="text-center comon_column">{{ $customer_info->delivery_man }}
                                                    </td>
                                                </tr>

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                                    <div class="product-list-area">
                                        {{-- @dump($customer_info) --}}
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    @if($customer_info->product_discount > 0)
                                                    <th class="text-nowrap">Dis.(Qty)</th>
                                                    @endif
                                                    <th>Sale(Qty)</th>
                                                    <th>Price</th>
                                                    <th class="text-nowrap">Sub Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($products as $product)
                                                    <tr>
                                                        <td class="text-center p-1 comon_column">{{ $product->product_code }}
                                                        </td>
                                                        <td class="text-left p-1 memo_product_title">
                                                            {{ $product->product_name }}</td>

                                                        <td class="text-right p-1 comon_column">
                                                            {{ $product->product_quantity }} {{ trans_choice('labels.'.$product->product->type, $product->product_quantity) }}
                                                        </td>
                                                        @if($customer_info->product_discount > 0)
                                                        <td class="text-right p-1 comon_column">
                                                            {{ $product->product_discount }} {{ trans_choice('labels.'.$product->product->type, $product->product_discount) }}
                                                        </td>
                                                        @endif
                                                        <td class="text-right p-1 comon_column">
                                                            {{ $product->product_quantity - $product->product_discount }}
                                                            {{ trans_choice('labels.'.$product->product->type, ($product->product_quantity - $product->product_discount)) }}
                                                        </td>
                                                        <td class="text-right p-1 comon_column">{{ $product->product_price }}/=
                                                        </td>
                                                        <td class="text-right p-1 comon_column">{{ $product->sub_total }}/=
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6">
                                                            Not Found!
                                                        </td>
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
                                        <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                                        <table class="calculation_below_table">
                                            <tr>
                                                <th>Total Price</th>
                                                <td>{{ $customer_info->total_price }}/=</td>
                                            </tr>
                                            @if (!empty($customer_info->price_discount))
                                                <tr>
                                                    <th>Discount</th>
                                                    <td>{{ $customer_info->price_discount ?? 0 }}/=</td>
                                                </tr>
                                            @endif
                                            @if (!empty($customer_info->vat))
                                                <tr>
                                                    <th>VAT</th>
                                                    <td>{{ $customer_info->vat ?? 0 }}/=</td>
                                                </tr>
                                            @endif

                                            @if (!empty($customer_info->carring))
                                                <tr>
                                                    <th>Carring</th>
                                                    <td>{{ $customer_info->carring ?? 0 }}/=</td>
                                                </tr>
                                            @endif

                                            @if (!empty($customer_info->other_charge))
                                                <tr>
                                                    <th>Others</th>
                                                    <td>{{ $customer_info->other_charge ?? 0 }}/=</td>
                                                </tr>
                                            @endif
                                            @if( !empty($customer_info->vat) || !empty($customer_info->carring) || !empty($customer_info->other_charge) || !empty($customer_info->price_discount))
                                            <tr>
                                                <th> Total: </th>
                                                <td>{{ $customer_info->total_price + $customer_info->vat + $customer_info->carring + $customer_info->other_charge - $customer_info->price_discount }}/=
                                                </td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <th>Previous Due</th>
                                                <td>{{ $customer_info->previous_due ?? 0 }}/=</td>
                                            </tr>
                                            <tr>
                                                <th>Grand Total</th>
                                                <td>{{ $customer_info->previous_due + $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge }}/=
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Payment Amount</th>
                                                <td>{{ $customer_info->payment ?? 0 }}/=</td>
                                            </tr>
                                            <tr>
                                                <th>Current Due</th>
                                                <td>{{ $total = $customer_info->total_price + $customer_info->previous_due - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge - $customer_info->payment }}/=
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="in-word-area py-3">
                                        <h4 class="text-left text-dark">In Words: {{ numberToWords($total) }}</h4>
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
