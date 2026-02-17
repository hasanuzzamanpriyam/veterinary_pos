@extends('layouts.print')  <!-- Extending the print layout -->

@section('page-title')
    Statement - {{ $supplier->company_name }} from {{ $start_date }} to {{ $end_date }}
@endsection

@push('style')
<style>
    @page {
        margin: 50px 30px;
    }
    body {
        font-size: 10px;
    }
    .supplier-area {
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
        .supplier-area {
            margin-top: 0;
        }
    }
</style>
@endpush

@section('main-content')
    <div class="supplier-area">
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
                        <h2>Supplier Statement</h2>
                        <p style="font-size: 16px; font-weight: 700">{{ $start_date }} To {{ $end_date }}</p>
                        @if(!empty($search_query))
                            <p>Search Result: <strong>{{$search_query}}</strong></p>
                        @endif
                    </td>
                    <td class="right" width="33%">
                        <h2>{{ $supplier->company_name }}</h2>
                        <p>{{ $supplier->address ? $supplier->address : '' }}</p>
                        <p>{{ $supplier->mobile ? 'Mobile: ' . $supplier->mobile : '' }}</p>
                    </td>
                </tr>
            </table>

        </div>

        <table class="printable table table-bordered mb-0 w-100" cellspacing="0">
            <thead>
                <tr class="text-center">
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Weight</th>
                    <th>Purchase</th>
                    <th>Payment</th>
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
                        'weight' => 0,
                        'sale_tk' => 0,
                        'collection_tk' => 0,
                        'balance' => 0
                    ];
                @endphp
                @foreach( $supplier_ledger_info as $report )
                    @php
                        if($report->type != 'prev' && $report->payment > 0){
                            $total_summary['payment_tk'] = $total_summary['payment_tk'] ?? 0;
                            $total_summary['payment_tk'] += $report->payment;
                        }

                        $x_total = $report->total_price - $report->price_discount - $report->vat - $report->carring - $report->other_charge;

                        if ($report->type == 'purchase' || $report->type == 'return') {
                            $total_summary['purchase_tk'] = $total_summary['purchase_tk'] ?? 0;
                            $total_summary['purchase_tk'] += $report->type == 'purchase' ? $x_total : -$x_total;
                        }

                    @endphp
                    @if( $report->type == 'other' )
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $report->balance;
                        @endphp
                        <tr>
                            <td class="text-center" style="white-space: nowrap;">{{date('d-m-Y', strtotime($report->date))}}</td>
                            <td class="text-center">{{$report->id}}</td>
                            <td class="text-left">Opening Balance</td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif ($report->type == 'prev')
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $report->balance;
                        @endphp
                        <tr>
                            <td class="text-center" style="white-space: nowrap;">--</td>
                            <td class="text-center">--</td>
                            <td class="text-left">Before Balance</td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class=""></td>
                            <td class="text-right"></td>
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $report->type == 'purchase' )
                        @php
                            $filtered_products = $products->where('transaction_id', $report->id);
                            $qty_summary = [];
                            $qty_summary_dis = [];
                            $qty_summary_purchase = [];
                            $qty_mt = 0;

                            $total_tk = $report->total_price - $report->price_discount;
                            $total_payment = $report->payment + $report->vat + $report->carring + $report->other_charge;
                            $balance = $total_tk - $total_payment;

                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] += $balance;
                        @endphp
                        <tr data-type="purchase">
                            <td class="text-center">{{date('d-m-Y', strtotime($report->date))}}</td>
                            <td class="text-center">{{$report->id}}</td>
                            <td class="text-left">
                                {{'Purchase'}} {{$report->transport_no}}{{$report->delivery_man && $report->transport_no ? ", " : ''}}{{$report->delivery_man}}
                            </td>

                            {{-- Quantity --}}
                            <td class="text-center">
                                @foreach ($filtered_products as $product)
                                    @php
                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                        $type = $product_info->type;
                                        $qty_summary_purchase[$type] = $qty_summary_purchase[$type] ?? 0;
                                        $qty_summary_purchase[$type] += ($product->quantity - $product->discount_qty);
                                        $qty_mt += $product->product->size->name * ($product->quantity - $product->discount_qty);
                                    @endphp
                                @endforeach
                                @php
                                    $total_summary['weight'] += $qty_mt;
                                    ksort($qty_summary_purchase);
                                @endphp
                                <div>
                                    @if( isset($qty_summary_purchase) && count($qty_summary_purchase) > 0)
                                        @foreach ($qty_summary_purchase as $key => $value)
                                            @if( $value > 0)
                                                @php
                                                    $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                    $total_summary['qty'][$key] += $value;
                                                @endphp
                                                {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            {{-- Weight --}}
                            <td class="text-center">{{$qty_mt / 1000}} MT</td>

                            {{-- Total Tk --}}
                            <td class="text-right">{{$total_tk > 0 ? number_format($total_tk) . '/=' : ''}}</td>

                            {{-- Payment --}}
                            <td class="text-right">{{$total_payment > 0 ? number_format($total_payment) . '/=' : ''}}</td>

                            {{-- Due Amount --}}
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $report->type == 'payment' )
                        @php
                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] -= $report->payment;
                        @endphp
                        <tr data-type="payment">
                            <td class="">{{date('d-m-Y', strtotime($report->date))}}</td>
                            <td class="text-center">{{$report->id}}</td>
                            <td class="text-left">
                                {{ $report->payment_by }}
                                {{ $report->bank_title ? ' - ' . $report->bank_title : '' }}
                                {{ $report->payment_remarks ? ' - ' . $report->payment_remarks : '' }}

                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{$report->payment > 0 ? number_format($report->payment) . '/=' : ''}}</td>
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
                        </tr>
                    @elseif( $report->type == 'return' )
                        @php
                            $filtered_products = $products->where('transaction_id', $report->id);
                            $qty_summary = [];
                            $qty_summary_return = [];
                            $qty_mt = 0;
                            $t_payment = $report->carring + $report->other_charge + $report->payment;
                            $total = $report->total_price - $report->price_discount + $report->carring + $report->other_charge;

                            $total_summary['balance'] = $total_summary['balance'] ?? 0;
                            $total_summary['balance'] -= $total;
                        @endphp
                        <tr data-type="return">
                            <td class="">
                                <span class="d-block text-nowrap">{{date('d-m-Y', strtotime($report->date))}}</span>
                            </td>
                            <td class="text-center">{{$report->id}}</td>
                            <td class="text-left">
                                <span class="d-block text-nowrap">Returned</span>

                            </td>
                            <td>
                                @foreach ($filtered_products as $product)
                                    @php
                                        $product_info = DB::table('products')->where('id', $product->product_id)->first();
                                        $type = $product_info->type;
                                        $qty_summary_return[$type] = $qty_summary_return[$type] ?? 0;
                                        $qty_summary_return[$type] += $product->quantity;
                                        $qty_mt += $product->product->size->name * $product->quantity;
                                    @endphp
                                @endforeach
                                @php
                                    $total_summary['weight'] -= $qty_mt;
                                    ksort($qty_summary_return);
                                @endphp
                                <div>
                                    @if( isset($qty_summary_return) && count($qty_summary_return) > 0)
                                        (-)
                                        @foreach ($qty_summary_return as $key => $value)
                                            @php
                                                $total_summary['qty'][$key] = $total_summary['qty'][$key] ?? 0;
                                                $total_summary['qty'][$key] -= $value;
                                            @endphp
                                            {{ $value }} {{trans_choice('labels.'.strtolower($key), $value)}}
                                        @endforeach
                                    @endif
                                </div>
                            </td>

                            {{-- Weight --}}
                            <td>-{{$qty_mt / 1000}} MT</td>

                            {{-- Total Tk --}}
                            <td class="text-right">-{{number_format($total)}}/=</td>

                            {{-- Payment --}}
                            <td class="text-right">{{$t_payment ? number_format($t_payment) . '/=' : ''}}</td>

                            {{-- Due Amount --}}
                            <td class="text-right">{{number_format($total_summary['balance'])}}/=</td>
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
                    <th>
                        @php
                            $weightT = $total_summary['weight'] / 1000;
                        @endphp
                        {{formatAmount($weightT)}}
                    </th>
                    <th class="text-right">{{formatAmount($total_summary['purchase_tk'] ?? 0)}}/=</th>
                    <th class="text-right">{{formatAmount($total_summary['payment_tk'] ?? 0)}}/=</th>
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
                        <p>Authorized Signature</p>
                    </div>
                </td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%" style="text-align: center;">
                    <div>
                        <hr>
                        <p>Supplier Signature</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endsection

