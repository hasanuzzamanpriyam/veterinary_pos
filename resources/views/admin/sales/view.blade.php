@extends('layouts.admin')

@section('page-title')
    Sales View
@endsection

@section('main-content')
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title p-3">
                <div class="header-title d-flex align-items-center gap-2">
                    <h2>Checkout</h2>
                    <a href="{{ route('sales.index', [ 'view' => 'v1' ]) }}" class="mr-auto ml-3 cursor-pointer"><i class="fa fa-close"></i></a>

                    <a href="{{ route('sales.invoice', $invoice) }}"
                        onClick="MyWindow=window.open('{{ route('sales.invoice', $invoice) }}','MyWindow','width=900,height=700'); return false;"
                        class="btn btn-primary btn-sm p-2">
                        Print <i class="fa fa-print text-white"></i>
                    </a>
                </div>
            </div>

            <div class="x_content p-3" style="max-width: 720px; margin: 0 auto; float: unset;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="text-center text-dark">Challan #{{$invoice }}</h2>
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
                                            {{ $customer_info->customer->address }}
                                        </td>
                                        <td>{{$customer_info->customer->mobile}}</td>
                                    </tr>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Gari No</th>
                                        <th>Delivery Man</th>
                                        <th>Remarks</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center comon_column">{{ $customer_info->store->name }}
                                        </td>
                                        <td class="text-center comon_column">{{ $customer_info->transport_no }}
                                        </td>
                                        <td class="text-center comon_column">{{ $customer_info->delivery_man }}
                                        </td>
                                        <td class="text-center comon_column">{{ $customer_info->remarks }}</td>
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
                            <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
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
                                    @php
                                        $total_summary = [
                                            'qty' => [],
                                            'dis_qty' => [],
                                            'sale_qty' => [],
                                            'price' => 0,
                                            'sub_total' => 0
                                        ];
                                    @endphp
                                    @forelse ($products as $product)
                                        @php
                                            $total_summary['qty'][$product->product->type] = $total_summary['qty'][$product->product->type] ?? 0;
                                            $total_summary['qty'][$product->product->type] += $product->quantity;
                                            $total_summary['dis_qty'][$product->product->type] = $total_summary['dis_qty'][$product->product->type] ?? 0;
                                            $total_summary['dis_qty'][$product->product->type] += $product->discount_qty;
                                            $total_summary['sale_qty'][$product->product->type] = $total_summary['sale_qty'][$product->product->type] ?? 0;
                                            $total_summary['sale_qty'][$product->product->type] += ($product->quantity - $product->discount_qty);
                                            $total_summary['price'] += $product->unit_price;
                                            $total_summary['sub_total'] += $product->total_price;
                                        @endphp
                                        <tr>
                                            <td class="text-center p-1 comon_column">{{ $product->product_code }}
                                            </td>
                                            <td class="text-left p-1 memo_product_title">
                                                {{ $product->product_name }}</td>

                                            <td class="text-center p-1 comon_column">
                                                {{ $product->quantity }} {{ trans_choice('labels.'.$product->product->type, $product->quantity) }}
                                            </td>
                                            @if($customer_info->product_discount > 0)
                                            <td class="text-center p-1 comon_column">
                                                {{ $product->discount_qty }} {{ trans_choice('labels.'.$product->product->type, $product->discount_qty) }}
                                            </td>
                                            @endif
                                            <td class="text-center p-1 comon_column">
                                                {{ $product->quantity - $product->discount_qty }}
                                                {{ trans_choice('labels.'.$product->product->type, ($product->quantity - $product->discount_qty)) }}
                                            </td>
                                            <td class="text-right p-1 comon_column">{{ formatAmount($product->unit_price) }}/=
                                            </td>
                                            <td class="text-right p-1 comon_column">{{ formatAmount($product->total_price) }}/=
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
                                        @if($customer_info->product_discount > 0)
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['dis_qty']) > 0)
                                                @foreach ($total_summary['dis_qty'] as $key => $value)
                                                    {{ $value }} {{ trans_choice('labels.'.$key, $value) }}
                                                @endforeach
                                            @endif
                                        </th>
                                        @endif
                                        <th class="text-center p-1 comon_column">
                                            @if ( count($total_summary['sale_qty']) > 0)
                                                @foreach ($total_summary['sale_qty'] as $key => $value)
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
                        <div class="calculation-area d-flex justify-content-end">
                            <!-- <h3 class="text-center text-dark">Billing Info</h3> -->
                            @php

                            // dump($customer_info);
                                $gTotal = $customer_info->previous_due + $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;
                                $prev_balance = $customer_info->balance + $customer_info->payment - $gTotal;
                            @endphp
                            <table class="calculation_below_table">
                                <tr>
                                    <th>Total Price</th>
                                    <td>{{ formatAmount($customer_info->total_price) }}/=</td>
                                </tr>
                                @if ($customer_info->price_discount > 0)
                                    <tr>
                                        <th>Discount</th>
                                        <td>{{ formatAmount($customer_info->price_discount) }}/=</td>
                                    </tr>
                                @endif
                                @if ($customer_info->vat > 0)
                                    <tr>
                                        <th>VAT</th>
                                        <td>{{ formatAmount($customer_info->vat) }}/=</td>
                                    </tr>
                                @endif

                                @if ($customer_info->carring > 0)
                                    <tr>
                                        <th>Carring</th>
                                        <td>{{ formatAmount($customer_info->carring) }}/=</td>
                                    </tr>
                                @endif

                                @if ($customer_info->other_charge > 0)
                                    <tr>
                                        <th>Others</th>
                                        <td>{{ formatAmount($customer_info->other_charge) }}/=</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th> Total: </th>
                                    <td>{{ formatAmount($customer_info->total_price + $customer_info->vat + $customer_info->carring + $customer_info->other_charge - $customer_info->price_discount) }}/=
                                    </td>
                                </tr>

                                <tr>
                                    <th>@if ($customer_info->balance >= 0)  Previous Due @else Advance Balance @endif</th>
                                    <td>{{ formatAmount($prev_balance) }}/=</td>
                                </tr>

                                <tr>
                                    <th>Grand Total</th>
                                    <td>{{ formatAmount($prev_balance  + $gTotal) }}/=
                                    </td>
                                </tr>
                                @if ($customer_info->payment > 0)
                                    <tr>
                                        <th>Collection Amount</th>
                                        <td>{{ formatAmount($customer_info->payment) }}/=</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Current Due</th>
                                    <td>{{ formatAmount($customer_info->balance) }}/=
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="in-word-area py-3">
                            <h4 class="text-left text-dark">In Words: {{ numberToWords($customer_info->balance) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="bottom-area py-3">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="customer-signature-area">
                                    <h4 class="text-center text-dark">Customer Signature</h4>
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

    {{-- invoice section --}}



@endsection
