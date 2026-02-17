@extends('layouts.print')  <!-- Extending the print layout -->

@section('page-title')
    Ledger - {{ $customer->name }} from {{ $start_date }} to {{ $end_date }}
@endsection

@push('style')
<style>
    @page {
        margin: 50px 30px;
    }
    body {
        font-size: 10px;
    }
    .customer-area {
        margin-top: 20px;
    }
    .header {
        margin-bottom: 20px;
        width: 100%;
    }

    .header td {
        font-size: 12px;
        padding: 5px 2px;
    }
    .header td > * {
        margin: 0;
        line-height: 1.2;
    }
    .header td.left {
        text-align: left;
    }
    .header td.middle {
        text-align: center;
        vertical-align: top;
    }
    .header td.right {
        text-align: right;
    }

    .header td.left h2,
    .header td.middle h2,
    .header td.right h2 {
        font-size: 20px;
    }

    .header td.left p,
    .header td.right p {
        font-size: 16px;
    }

    .print-button-area {
        text-align: center;
    }
    .printable {
        width: 100%;
    }
    .printable thead input[type="checkbox"],
    .printable thead label {
        cursor: pointer;
    }
    .printable label {
        background: unset;
        color: unset;
        text-align: center !important;
        margin: 0;
    }
    .printable td, .printable th {
        padding: 2px 2px;
        border-right: 1px solid #8d8d8d;
        border-bottom: 1px solid #8d8d8d;
    }
    .printable {
        border-top: 1px solid #8d8d8d;
        border-left: 1px solid #8d8d8d;
    }

    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-left {
        text-align: left;
    }
    .mb-0 {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        line-height: 1;
    }
    .d-block {
        display: block;
    }
    .text-nowrap {
        white-space: nowrap;
    }

    .footer {
        margin-top: 100px;
    }

    @media print {
        @page {
            size: auto; /* Set the page size to landscape */
        }
        body {
            width: 100%;
        }
        .customer-area {
            margin-top: 0;
        }
    }
</style>
@endpush

@section('main-content')
    <div class="customer-area">
        <div class="header">
            <table class="header mb-0 w-100" valign="top" cellspacing="0">
                <tr class="top-header">
                    <td colspan="3" class="text-center">
                        <p style="font-size: 16px">বিসমিল্লাহির রাহমানির রাহিম</p>
                    </td>
                </tr>
                <tr>
                    <td class="left" width="33%">
                        <h2 style="font-size: 24px">Firoz Enterprise</h2>
                        <p>Parila Bazar, Paba, Rajshahi</p>
                        <p>Mobile: 01712 203045</p>
                    </td>
                    <td class="middle" width="33%">
                        <h2>Customer Ledger</h2>
                        <p style="font-size: 16px; font-weight: 700">{{ $start_date }} To {{ $end_date }}</p>
                        @if(!empty($search_query))
                            <p>Search Result: <strong>{{$search_query}}</strong></p>
                        @endif
                    </td>
                    <td class="right" width="33%">
                        <h2>{{ $customer->name }}</h2>
                        <p>{{ $customer->address ? $customer->address : '' }}</p>
                        <p>{{ $customer->mobile ? 'Mobile: ' . $customer->mobile : '' }}</p>
                    </td>
                </tr>
            </table>

        </div>

        <table class="printable table table-bordered mb-0 w-100" cellspacing="0">
            <thead>
                <tr style="text-align: center">
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Particular</th>
                    <th>Quantity</th>
                    <th>Sale (Tk)</th>
                    <th>Collection</th>
                    <th>Due Amount</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $g_total_sumarry = [];
                @endphp
                @php
                    $total_summary = [
                        'qty' => [],
                        'sale_tk' => 0,
                        'collection_tk' => 0,
                        'balance' => 0
                    ];
                @endphp
                @foreach( $customer_ledger_info as $customer_info )
                    @php
                        if($customer_info->type != 'prev' && $customer_info->payment > 0){
                            $total_summary['collection_tk'] = $total_summary['collection_tk'] ?? 0;
                            $total_summary['collection_tk'] += $customer_info->payment;
                        }

                        $x_total = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;

                        if ($customer_info->type == 'sale' || $customer_info->type == 'return') {
                            $total_summary['sale_tk'] = $total_summary['sale_tk'] ?? 0;
                            $total_summary['sale_tk'] += $customer_info->type == 'sale' ? $x_total : -$x_total;
                        }

                    @endphp
                    @if( $customer_info->type == 'other' )
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $customer_info->balance;
                        @endphp
                        <tr>
                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>
                            <td class="text-center">{{$customer_info->id}}</td>
                            <td class="text-left">{{$customer_info->received_by}}</td>
                            <td></td>
                            <td class=""></td>
                            <td class="text-right">{{number_format($customer_info->payment ?? 0)}}/=</td>
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $customer_info->type == 'prev' )
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $customer_info->balance;
                        @endphp
                        <tr>
                            <td class="text-center">--</td>
                            <td class="text-center">--</td>
                            <td class="text-left">Before Balance</td>
                            <td></td>
                            <td class=""></td>
                            <td class="text-right"></td>
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $customer_info->type == 'sale' )
                        @php
                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                            $qty_summary = [];
                            $total_tk = $customer_info->total_price - $customer_info->price_discount + $customer_info->vat + $customer_info->carring + $customer_info->other_charge;
                            $balance  = $total_tk - $customer_info->payment;
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $balance;
                        @endphp
                        <tr data-type="sale">
                            {{-- Date --}}
                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>

                            {{-- Invoice --}}
                            <td class="text-center">{{$customer_info->id}}</td>

                            {{-- Particular --}}
                            <td class="text-left">
                                @foreach ($products as $product)
                                @php
                                    $type = $product->product->type;
                                @endphp
                                    @if($product->transaction_id == $customer_info->id)
                                        <p class="mb-0">
                                            {{ $product->product_code}} -
                                            {{ $product->product_name}} -
                                            {{ $product->quantity - $product->discount_qty}} {{ trans_choice('labels.'.$type, ($product->quantity - $product->discount_qty))}} -
                                            {{ $product->unit_price}}/=
                                            {{$product->total_price}}/=
                                        </p>

                                    @endif
                                @endforeach
                            </td>

                            {{-- Quantity --}}
                            <td class="text-center">
                                @foreach ($filtered_products as $product)
                                    @php
                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                        $type = $product_info->type;
                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                        $qty_summary[$type] += ($product->quantity - $product->discount_qty);

                                    @endphp
                                @endforeach
                                <div>
                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                        @foreach ($qty_summary as $key => $value)
                                            @php
                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                $total_summary['qty'][$key] += $value;
                                            @endphp
                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            {{-- Total (Tk) --}}
                            <td class="text-right">{{ number_format($total_tk) }}/=</td>

                            {{-- Collection --}}
                            @if($customer_info->payment)
                                <td class="text-right">{{number_format($customer_info->payment)}}/=</td>
                            @else
                                <td></td>
                            @endif

                            {{-- Due (Tk) --}}
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $customer_info->type == 'collection' )
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] -= $customer_info->payment;
                        @endphp
                        <tr>
                            {{-- Date --}}
                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>

                            {{-- Invoice --}}
                            <td class="text-center">{{$customer_info->id}}</td>

                            {{-- Particular --}}
                            <td class="text-left">
                                {{ 'Collection' }} :
                                {{ $customer_info->payment_by }}
                                {{ $customer_info->bank_title ? ' : ' . $customer_info->bank_title : '' }}
                                {{ $customer_info->received_by ? ' - ' . $customer_info->received_by : '' }}
                            </td>

                            {{-- Quantity --}}
                            <td></td>

                            {{-- Total (Tk) --}}
                            <td></td>

                            {{-- Collection --}}
                            @if($customer_info->payment)
                                <td class="text-right">{{number_format($customer_info->payment)}}/=</td>
                            @else
                            <td></td>
                            @endif

                            {{-- Due (Tk) --}}
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $customer_info->type == 'return' )
                        @php
                            $filtered_products = $products->where('transaction_id', $customer_info->id);
                            $qty_summary = [];
                            $total = $customer_info->total_price - ($customer_info->carring + $customer_info->other_charge);

                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] -= $total;
                        @endphp
                        <tr data-type="return">
                            {{-- Date --}}
                            <td class="text-center">{{date('d-m-Y', strtotime($customer_info->date))}}</td>

                            {{-- Invoice --}}
                            <td class="text-center">{{$customer_info->id}}</td>

                            {{-- Particular --}}
                            <td class="text-left">
                                @foreach ($products as $product)
                                    @php
                                        $type = $product->product->type;
                                    @endphp
                                    @if($product->transaction_id == $customer_info->id)
                                        <p class="mb-0">
                                            {{ $product->product_code}} -
                                            {{ $product->product_name}} -
                                            {{ $product->quantity}} {{ trans_choice('labels.'.$type, $product->quantity)}} -
                                            {{ $product->unit_price}}/=
                                            {{$product->total_price}}/=
                                        </p>
                                    @endif
                                @endforeach
                            </td>

                            {{-- Quantity --}}
                            <td class="text-center">
                                @foreach ($filtered_products as $product)
                                    @php
                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                        $type = $product_info->type;
                                        $qty_summary[$type] = $qty_summary[$type] ?? 0;
                                        $qty_summary[$type] += $product->quantity;
                                    @endphp
                                @endforeach
                                <div>
                                    @if( isset($qty_summary) && count($qty_summary) > 0)
                                        @foreach ($qty_summary as $key => $value)
                                            @php
                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                $total_summary['qty'][$key] -= $value;
                                            @endphp
                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            {{-- Total (Tk) --}}
                            <td class="text-right">{{ number_format($total) }}/=</td>

                            {{-- Collection --}}
                            <td></td>

                            {{-- Due (Tk) --}}
                            <td class="text-right">{{ number_format($total_summary['balance']) }}/=</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        @if (isset($total_summary) && count($total_summary['qty']) > 0)
                            @php
                                ksort($total_summary['qty']);
                            @endphp
                            @foreach ($total_summary['qty'] as $key => $value)
                                <div>{{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}</div>
                            @endforeach
                        @endif
                    </th>
                    <th class="text-right">{{formatAmount($total_summary['sale_tk'] ?? 0)}}/=</th>
                    <th class="text-right">{{formatAmount($total_summary['collection_tk'] ?? 0)}}/=</th>
                    <th class="text-right">{{formatAmount($total_summary['balance'] ?? 0)}}/=</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="footer">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="25%" style="text-align: center">
                    <div>
                        <hr>
                        <p>Customer Signature</p>
                    </div>
                </td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%" style="text-align: center;">
                    <div>
                        <hr>
                        <p>Authorized Signature</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

@endsection

