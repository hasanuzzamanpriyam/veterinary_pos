@extends('layouts.admin')

@section('page-title')
    Purchase Invoice
@endsection

@section('main-content')

    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                {{-- <div class="header-title d-flex align-items-center gap-2">
                    <h2>Checkout</h2>
                    <a href="{{ route('purchase.index') }}" class="mr-auto ml-3 cursor-pointer"><i
                            class="fa fa-close"></i></a>
                    <a href="#" onclick="MyWindow=window.open('#','MyWindow','width=900,height=600'); return false;"
                        class="btn btn-primary btn-sm p-2">Print <i class="fa fa-print text-white"></i></a>
                </div> --}}
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
                                        <td>{{optional($supplier_info->supplier)->company_name}}</td>
                                        <td>{{optional($supplier_info->supplier)->address}}</td>
                                        <td>{{optional($supplier_info->supplier)->mobile}}</td>

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
                                        <td>{{optional($supplier_info->warehouse)->name}}</td>
                                        <td>{{$supplier_info->transport_no}}</td>
                                        <td>{{$supplier_info->delivery_man}}</td>
                                        <td>{{$supplier_info->supplier_remarks}}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>VAT</th>
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
                                            'total' => 0,
                                            'sub_total' => 0,
                                            'total_discount' => 0,
                                            'total_vat' => 0,
                                            'net_amount' => 0,
                                        ];
                                    @endphp
                                    @forelse ($products as $product)
                                        @php
                                            $sizeName =
                                                $product->product?->size?->name ?? ($product->product?->type ?? 'N/A');
                                            $total_summary['qty'][$sizeName] = $total_summary['qty'][$sizeName] ?? 0;
                                            $total_summary['qty'][$sizeName] += $product->quantity;
                                            $total_summary['dis_qty'][$sizeName] =
                                                $total_summary['dis_qty'][$sizeName] ?? 0;
                                            $total_summary['dis_qty'][$sizeName] += $product->discount_qty;
                                            $total_summary['purchase_qty'][$sizeName] =
                                                $total_summary['purchase_qty'][$sizeName] ?? 0;
                                            $total_summary['purchase_qty'][$sizeName] +=
                                                $product->quantity - $product->discount_qty;
                                            $total_summary['price'] += $product->unit_price;
                                            $total_summary['total'] += ($product->quantity - $product->discount_qty) * $product->unit_price;
                                            $total_summary['total_discount'] += $product->discount ?? 0;
                                            $total_summary['total_vat'] += $product->vat ?? 0;
                                            $total_summary['sub_total'] += $product->total_price;
                                        @endphp
                                        <tr>

                                            <td class="text-center p-1">{{$product->product->barcode ?? $product->product_code}}</td>
                                            <td class="text-left p-1">{{$product->product_name}}</td>
                                            <td class="text-center p-1">{{$product->quantity}}
                                                {{$sizeName}}</td>
                                            @if($supplier_info->product_discount > 0)
                                                <td class="text-center p-1">{{$product->discount_qty}}
                                                    {{$sizeName}}
                                                </td>
                                            @endif
                                            <td class="text-center p-1">{{$product->quantity - $product->discount_qty}}
                                                {{$sizeName}}
                                            </td>
                                            <td class="text-right p-1">{{formatAmount($product->unit_price)}}/=</td>
                                            <td class="text-right p-1">{{formatAmount(($product->quantity - $product->discount_qty) * $product->unit_price)}}/=</td>
                                            <td class="text-right p-1">{{formatAmount($product->discount ?? 0)}}/=</td>
                                            <td class="text-right p-1">{{formatAmount($product->vat ?? 0)}}/=</td>
                                            <td class="text-right p-1">{{formatAmount($supplier_info->total_price)}}/=</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">
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
                                                @if (count($total_summary['qty']) > 0)
                                                    @foreach ($total_summary['qty'] as $key => $value)
                                                        {{ $value }} {{ $key }}
                                                    @endforeach
                                                @endif
                                            </th>
                                            @if ($supplier_info->product_discount > 0)
                                                <th class="text-center p-1 comon_column">
                                                    @if (count($total_summary['dis_qty']) > 0)
                                                        @foreach ($total_summary['dis_qty'] as $key => $value)
                                                            {{ $value }} {{ $key }}
                                                        @endforeach
                                                    @endif
                                                </th>
                                            @endif
                                            <th class="text-center p-1 comon_column">
                                                @if (count($total_summary['purchase_qty']) > 0)
                                                    @foreach ($total_summary['purchase_qty'] as $key => $value)
                                                        {{ $value }} {{ $key }}
                                                    @endforeach
                                                @endif
                                            </th>
                                            <th class="text-right p-1 comon_column"></th>
                                            <th class="text-right p-1 comon_column">
                                                {{ formatAmount($total_summary['total']) }}/=</th>
                                            <th class="text-right p-1 comon_column">
                                                {{ formatAmount($total_summary['total_discount']) }}/=</th>
                                            <th class="text-right p-1 comon_column">
                                                {{ formatAmount($total_summary['total_vat']) }}/=</th>
                                            <th class="text-right p-1 comon_column">
                                                {{ formatAmount($supplier_info->total_price) }}/=</th>
                                        </tr>
                                    </tfoot>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="calculation-area d-flex justify-content-end">
                            <table class="calculation_below_table">
                                <tr>
                                    <th>Total Price</th>
                                    <td>{{$supplier_info->total_price}}/=</td>
                                </tr>
                                @if(!empty($supplier_info->price_discount))
                                    <tr>
                                        <th>Discount</th>
                                        <td>{{$supplier_info->price_discount ?? 0}}/=</td>
                                    </tr>
                                @endif
                                @if(!empty($supplier_info->vat))
                                    <tr>
                                        <th>Vat</th>
                                        <td>{{$supplier_info->vat ?? 0}}/=</td>
                                    </tr>
                                @endif
                                @if(!empty($supplier_info->carring))
                                    <tr>
                                        <th>Carring</th>
                                        <td>{{$supplier_info->carring ?? 0}}/=</td>
                                    </tr>
                                @endif
                                @if(!empty($supplier_info->other_charge))
                                    <tr>
                                        <th>Others</th>
                                        <td>{{$supplier_info->other_charge ?? 0}}/=</td>
                                    </tr>
                                @endif
                                @if(!empty($supplier_info->old_due))
                                    <tr>
                                        <th>Previous Due</th>
                                        <td>{{$supplier_info->old_due ?? 0}}/=</td>
                                    </tr>
                                @endif
                                {{-- @dump($supplier_info) --}}
                                <tr>
                                    <th>Grand Total</th>
                                    <td class="grand-total">
                                        {{$supplier_info->old_due + $supplier_info->total_price - ($supplier_info->price_discount + $supplier_info->vat + $supplier_info->carring + $supplier_info->other_charge)}}/=
                                    </td>
                                </tr>
                                <tr>
                                    <th>Previous Due</th>
                                    <td class="previous-due">{{$supplier_info->previous_due}}/=</td>
                                </tr>
                                <tr>
                                    <th>Total Due</th>
                                    <td>{{$supplier_info->previous_due + $supplier_info->total_price}}/=</td>
                                </tr>
                                <tr>
                                    <th>Payment Amount</th>
                                    <td>{{$supplier_info->payment ?? 0}}/=</td>
                                </tr>
                                <tr>
                                    <th>Due Amount</th>
                                    <td>{{$total = $supplier_info->current_due}}/=</td>
                                </tr>
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

@endsection
