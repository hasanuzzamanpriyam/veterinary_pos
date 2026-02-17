@extends('layouts.admin')

@section('page-title')
Purchase View
@endsection

@section('main-content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title p-3">
            <div class="header-title d-flex align-items-center gap-2">
                <h2>Checkout</h2>
                <a href="{{ route('purchase.index', ['view' => 'v1']) }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>
                <a href="{{ route('purchase.print', $supplier_info->id)}}" onclick="MyWindow=window.open('{{ route('purchase.print',$supplier_info->id)}}','MyWindow','width=900,height=600'); return false;" class="btn btn-primary btn-sm p-2">Print <i class="fa fa-print text-white"></i></a>
            </div>
        </div>

        <div class="x_content" style="max-width: 720px; margin: 0 auto; float: unset;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 class="text-center text-dark">Invoice #{{$supplier_info->id}}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                    <div class="product-list-area">
                        <table class="table table-striped table-bordered table-sm">
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
                                    <td>{{date('d-m-Y', strtotime($supplier_info->date))}}</td>
                                    <td>{{$supplier_info->supplier->company_name}}</td>
                                    <td>{{$supplier_info->supplier->address}}</td>
                                    <td>{{$supplier_info->supplier->mobile}}</td>

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
                                    <td>{{$supplier_info->warehouse->name}}</td>
                                    <td>{{$supplier_info->transport_no}}</td>
                                    <td>{{$supplier_info->delivery_man}}</td>
                                    <td>{{$supplier_info->supplier_remarks}}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- @dump($supplier_info) --}}

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 product_list_table">
                    <div class="product-list-area">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    @if($supplier_info->product_discount > 0)
                                    <th>Dis.(Qty)</th>
                                    @endif
                                    <th>Purchase(Qty)</th>
                                    <th>Price</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_summary = [
                                        'qty' => [],
                                        'dis_qty' => [],
                                        'purchase_qty' => [],
                                        'price' => 0,
                                        'sub_total' => 0
                                    ];
                                @endphp
                                {{-- @dump($products) --}}
                                @forelse ($products as $product)
                                    @php
                                        $total_summary['qty'][$product->product->type] = $total_summary['qty'][$product->product->type] ?? 0;
                                        $total_summary['qty'][$product->product->type] += $product->quantity;
                                        $total_summary['dis_qty'][$product->product->type] = $total_summary['dis_qty'][$product->product->type] ?? 0;
                                        $total_summary['dis_qty'][$product->product->type] += $product->discount_qty;
                                        $total_summary['purchase_qty'][$product->product->type] = $total_summary['purchase_qty'][$product->product->type] ?? 0;
                                        $total_summary['purchase_qty'][$product->product->type] += ($product->quantity - $product->discount_qty);
                                        $total_summary['price'] += $product->unit_price;
                                        $total_summary['sub_total'] += $product->total_price;
                                    @endphp

                                    <tr>

                                        <td class="text-center p-1">{{$product->product_code}}</td>
                                        <td class="text-left p-1">{{$product->product_name}}</td>
                                        <td class="text-center p-1">{{$product->quantity}} {{ trans_choice('labels.'.$product->product->type, $product->quantity) }}</td>
                                        @if($supplier_info->product_discount > 0)
                                        <td class="text-center p-1">{{$product->discount_qty}} {{ trans_choice('labels.'.$product->product->type, $product->discount_qty) }}</td>
                                        @endif
                                        <td class="text-center p-1">{{$product->quantity-$product->discount_qty}} {{ trans_choice('labels.'.$product->product->type, $product->quantity-$product->discount_qty) }} </td>
                                        <td class="text-right p-1">{{formatAmount($product->unit_price)}}/=</td>
                                        <td class="text-right p-1">{{formatAmount($product->total_price)}}/=</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
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
                                        @if($supplier_info->product_discount > 0)
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['dis_qty']) > 0)
                                                @foreach ($total_summary['dis_qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>
                                        @endif
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['purchase_qty']) > 0)
                                                @foreach ($total_summary['purchase_qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>
                                        <th class="text-right p-1 comon_column"></th>
                                        <th class="text-right p-1 comon_column">{{formatAmount($total_summary['sub_total'])}}/=</th>
                                    </tr>
                                </tfoot>
                            @endif

                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{-- @dump($supplier_info) --}}
                    <div class="calculation-area d-flex justify-content-end">
                        @php
                            $total_tk = $supplier_info->total_price - $supplier_info->price_discount;
                            $gTotal = $supplier_info->total_price - $supplier_info->price_discount - $supplier_info->vat - $supplier_info->carring - $supplier_info->other_charge;
                            $prev_balance = $supplier_info->balance != 0 ? $supplier_info->balance + $supplier_info->payment - $gTotal : 0;
                        @endphp
                        <table class="calculation_below_table">
                            <tr><td class="text-left">Total Price</td><td>{{formatAmount($supplier_info->total_price)}}/=</td></tr>
                            @if($supplier_info->price_discount > 0)
                            <tr><td class="text-left">Discount</td><td>{{formatAmount($supplier_info->price_discount ?? 0)}}/=</td></tr>
                            @endif
                            <tr><td class="text-left"><b>Total Tk</b></td><td><b>{{formatAmount($total_tk)}}/=</b></td></tr>
                            @if (abs($prev_balance) > 0)
                            <tr><td class="text-left">Previous Due</td><td>{{formatAmount($prev_balance ?? 0)}}/=</td></tr>
                            @endif
                            <tr><td class="text-left"><b>Current Due</b></td><td><b>{{formatAmount($prev_balance + $total_tk)}}/=</b></td></tr>
                            @if(abs($supplier_info->vat) > 0)
                            <tr><td class="text-left">VAT</td><td>{{formatAmount($supplier_info->vat ?? 0)}}/=</td></tr>
                            @endif
                            @if(abs($supplier_info->carring) > 0)
                            <tr><td class="text-left">Carring</td><td>{{formatAmount($supplier_info->carring ?? 0)}}/=</td></tr>
                            @endif
                            @if(abs($supplier_info->other_charge) > 0)
                            <tr><td class="text-left">Others</td><td>{{formatAmount($supplier_info->other_charge ?? 0)}}/=</td></tr>
                            @endif
                            @if (abs($supplier_info->payment) > 0)
                            <tr><td class="text-left">Payment</td><td>{{formatAmount($supplier_info->payment ?? 0)}}/=</td></tr>
                            @endif
                            <tr><td class="text-left">Total Payment</td><td>{{formatAmount($total_payment = $supplier_info->payment + $supplier_info->other_charge + $supplier_info->carring + $supplier_info->vat)}}/=</td></tr>
                            <tr><td class="text-left">Balance</td><td>{{formatAmount($total = $supplier_info->balance)}}/=</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="in-word-area py-3">
                        <h4 class="text-left text-dark">In Words: {{numberToWords($total)}}</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="bottom-area py-3">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="customer-signature-area">
                                <h4 class="text-center text-dark">Supplier Signature</h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="supplier-signature-area">
                                <h4 class="text-center text-dark">Authorized Signature</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="bottom-area">
                        <div class="col-lg-4 col-md-4 col-sm-6">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="thanks-area">
                                <h5 class="text-center text-dark"><small>Thank you for shopping with us</small></h5>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
