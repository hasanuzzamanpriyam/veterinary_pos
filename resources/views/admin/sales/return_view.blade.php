@extends('layouts.admin')

@section('page-title')
Sales Return View
@endsection

@section('main-content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">

            <div class="header-title d-flex align-items-center gap-2">
                <h2>Sales Return Invoice</h2>
                <a href="{{ route('sales.return.index') }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>

                <a href="{{ route('sales.return.invoice', $customer_info->id) }}"
                    onClick="MyWindow=window.open('{{ route('sales.return.invoice', $customer_info->id) }}','MyWindow','width=900,height=600'); return false;"
                    class="btn btn-primary btn-sm p-2">
                    Print <i class="fa fa-print text-white"></i>
                    </a>
            </div>
        </div>
        <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="col-lg-12 col-md-21 col-sm-12">
                <div class="row">
                    <div class="col-lg-12 col-md-21 col-sm-12">
                        <h2 class="text-center text-dark">Invoice: {{$customer_info->id}}</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="supplier-info-area">
                            <table class="table table-striped table-bordered table-sm text-left">
                                <thead>
                                    <tr>
                                        <th class="text-left">Return Date</th>
                                        <th class="text-left">Customer Name</th>
                                        <th class="text-left">Address</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                                        <td class="text-left">{{$customer_info->customer->name}}</td>
                                        <td class="text-left">{{$customer_info->customer->address}}</td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th class="text-left">Return Invoice No</th>
                                        <th class="text-left">Store/Warehouse</th>
                                        <th class="text-left">Remarks</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="text-left">{{$customer_info->id}}</td>
                                        <td class="text-left">{{$customer_info->store->name}}</td>
                                        <td class="text-left">{{$customer_info->remarks}}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="product-list-area">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th class="text-left">Name</th>
                                        <th>Return Qty</th>
                                        <th>Price</th>
                                        <th>Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_summary = [
                                            'qty' => [],
                                            'sale_qty' => [],
                                            'price' => 0,
                                            'sub_total' => 0
                                        ];
                                    @endphp
                                    @forelse ($products as $product)
                                        @php
                                            $total_summary['qty'][$product->product->type] = $total_summary['qty'][$product->product->type] ?? 0;
                                            $total_summary['qty'][$product->product->type] += $product->quantity;
                                            $total_summary['price'] += $product->unit_price;
                                            $total_summary['sub_total'] += $product->total_price;
                                        @endphp
                                        <tr>
                                            <td class="text-center p-1">{{$product->product_code}}</td>
                                            <td class="text-left p-1">{{$product->product_name}}</td>
                                            <td class="text-center p-1">{{$product->quantity}} {{trans_choice('labels.'.$product->product->type, $product->quantity)}}</td>
                                            <td class="text-right p-1">{{formatAmount($product->unit_price)}}/=</td>
                                            <td class="text-right p-1">{{formatAmount($product->total_price)}}/=</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                Not Found!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if (count($products) > 0)
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['qty']) > 0)
                                                @foreach ($total_summary['qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>
                                        <th class="text-right p-1 comon_column"></th>
                                        <th class="text-right p-1 comon_column">{{number_format($total_summary['sub_total'])}}/=</th>
                                    </tr>
                                </tfoot>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="total-area py-3">
                            <div class="calculation-area d-flex justify-content-end">
                                {{-- @dump($customer_info) --}}
                                <table class="calculation_below_table">
                                    <tr><th>Total Price</th><td>{{number_format($customer_info->total_price)}}/=</td></tr>

                                    @if( $customer_info->carring > 0)
                                    <tr><th>Carring</th><td>{{number_format($customer_info->carring)}}/=</td></tr>
                                    @endif
                                    @if( $customer_info->other_charge > 0)
                                    <tr><th>Others</th><td>{{number_format($customer_info->other_charge)}}/=</td></tr>
                                    @endif
                                    <tr><th>Grand Total</th>
                                        <td>
                                            @php
                                                $gTotal = $customer_info->total_price + ($customer_info->carring+$customer_info->other_charge);
                                            @endphp
                                            {{number_format($gTotal)}}/=
                                        </td>
                                    </tr>


                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="in-word-area py-3">
                            <h4 class="text-left text-dark">In Words: {{numberToWords($gTotal)}}</h4>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col">
                        <div class="customer-signature-area">
                            <h4 class="text-left text-dark">Customer Signature</h4>
                        </div>
                    </div>
                    <div class="col">
                    </div>
                    <div class="col">
                        <div class="supplier-signature-area">
                            <h4 class="text-right text-dark">Authorized Signature</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h5 class="text-center text-dark"><small>Thank you for shopping with us</small></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



