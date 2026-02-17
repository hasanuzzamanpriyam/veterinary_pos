@extends('layouts.print')  <!-- Extending the print layout -->

@section('page-title')
    Customer List
@endsection

@push('style')
<style>
    @page {
        margin: 30px 30px;
    }
    body {
        font-size: 9px;
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
        white-space: nowrap;
        text-align: center;
    }
    .printable td.text-right, .printable th.text-right {
        text-align: right;
    }
    .printable td.text-left, .printable th.text-left {
        text-align: left;
    }
    .printable td.text-center, .printable th.text-center {
        text-align: center;
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
        display: block !important;
    }
    .d-inline-block {
        display: inline-block !important;
    }
    .text-nowrap {
        white-space: nowrap !important;
    }

    @media print {
        @page {
            size: auto; /* Set the page size to landscape */
        }
        body {
            width: 100%;
        }
    }
</style>
@endpush

@section('main-content')
<div class="main-content-area">
    <div class="header">
        <table class="header mb-0 w-100" valign="top" cellspacing="0">
            <tr class="top-header">
                <td class="text-center">
                    <p style="font-size: 16px">বিসমিল্লাহির রাহমানির রাহিম</p>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <h2 style="font-size: 24px">Firoz Enterprise</h2>
                    <p>Parila Bazar, Paba, Rajshahi</p>
                    <p>Mobile: 01712 203045</p>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <h2>Customer List</h2>
                    @if(!empty($queryString))
                        <p>Search Result: <strong>{{$queryString}}</strong></p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @php
        $visible_cols = [
            'id',
            'customer_name',
            'address',
            'phone',
            'ledger',
            'price_group',
            'type',
            'credit_limit',
            'quantity',
            'discount_quantity',
            'return_quantity',
            'sale_quantity',
            'sale_amount',
            'return_amount',
            'discount_amount',
            'vat',
            'carring',
            'others',
            'total_amount',
            'old_due',
            'collection',
            'balance',
        ];
    @endphp

    <div class="">
        <table class="printable table table-bordered table-striped mb-0 w-100" cellspacing="0">
            <thead>
                <tr>
                    @if (in_array('id', $visible_cols))
                        <th class="all">SL</th>
                    @endif
                    @if (in_array('customer_name', $visible_cols))
                        <th class="text-left">Customer Name</th>
                    @endif
                    @if (in_array('address', $visible_cols))
                        <th class="text-left">Address</th>
                    @endif
                    @if (in_array('phone', $visible_cols))
                        <th class="text-left">Phone</th>
                    @endif
                    @if (in_array('ledger', $visible_cols))
                        <th class="all">Ledger</th>
                    @endif
                    @if (in_array('price_group', $visible_cols))
                        <th class="all">Price Group</th>
                    @endif
                    @if (in_array('type', $visible_cols))
                        <th class="all">Type</th>
                    @endif
                    @if (in_array('credit_limit', $visible_cols))
                        <th class="all">Credit Limit</th>
                    @endif
                    @if (in_array('quantity', $visible_cols))
                        <th class="all" style="max-width: 100px">Quantity</th>
                    @endif
                    @if (in_array('discount_quantity', $visible_cols))
                        <th class="all" style="max-width: 100px">Dis. Qty</th>
                    @endif
                    @if (in_array('return_quantity', $visible_cols))
                        <th class="all" style="max-width: 100px">Return Qty</th>
                    @endif
                    @if (in_array('sale_quantity', $visible_cols))
                        <th class="all" style="max-width: 100px">Pur. Qty</th>
                    @endif
                    @if (in_array('sale_amount', $visible_cols))
                        <th class="all">Pur. (TK)</th>
                    @endif
                    @if (in_array('return_amount', $visible_cols))
                        <th class="all">Return</th>
                    @endif
                    @if (in_array('discount_amount', $visible_cols))
                        <th class="all">Dis. (TK)</th>
                    @endif
                    @if (in_array('vat', $visible_cols))
                        <th class="all">VAT</th>
                    @endif
                    @if (in_array('carring', $visible_cols))
                        <th class="all">Carring</th>
                    @endif
                    @if (in_array('others', $visible_cols))
                        <th class="all">Others</th>
                    @endif
                    @if (in_array('total_amount', $visible_cols))
                        <th class="all">Total (TK)</th>
                    @endif
                    @if (in_array('old_due', $visible_cols))
                        <th class="all">Old Due</th>
                    @endif
                    @if (in_array('collection', $visible_cols))
                        <th class="all">Total Payment</th>
                    @endif
                    @if (in_array('balance', $visible_cols))
                        <th class="all">Balance</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $g_total_summary = [];
                @endphp
                @foreach ($customers as $customer)

                    @php
                        $total_sales = $customer->total_sales ?? 0;
                        $total_return = $customer->total_returns ?? 0;
                        $total_discount = $customer->total_price_discounts ?? 0;
                        $total_vat = $customer->total_vat ?? 0;
                        $total_carring = $customer->total_carring ?? 0;
                        $total_others = $customer->total_others ?? 0;
                        $total_collection = $customer->total_collections ?? 0;
                        $prev_due = $customer->previous_due ?? 0;
                        $balance = $customer->balance;

                        $total_qty = [];
                        $total_sale_discount_qty = [];
                        $total_return_qty = [];
                        $total_sale_qty = [];
                        $total_tk = $total_sales - $total_return - $total_discount + $total_vat + $total_carring + $total_others;
                        $qty_summary = $group_by_customer->get($customer->id);
                        if (is_array($qty_summary) && count($qty_summary) > 0) {
                            foreach($qty_summary as $key => $value) {
                                $total_qty[$key] = $total_qty[$key] ?? 0;
                                $total_sale_discount_qty[$key] = $total_sale_discount_qty[$key] ?? 0;
                                $total_return_qty[$key] = $total_return_qty[$key] ?? 0;
                                $total_sale_qty[$key] = $total_sale_qty[$key] ?? 0;

                                $total_qty[$key] += $value['sale'];
                                $total_sale_discount_qty[$key] += $value['discount'];
                                $total_return_qty[$key] += $value['return'];
                                $total_sale_qty[$key] += $value['sale'] - $value['discount'] - $value['return'];

                                $g_total_summary['qty'][$key] = $g_total_summary['qty'][$key] ?? 0;
                                $g_total_summary['qty'][$key] += $value['sale'];
                                $g_total_summary['dis_qty'][$key] = $g_total_summary['dis_qty'][$key] ?? 0;
                                $g_total_summary['dis_qty'][$key] += $value['discount'];
                                $g_total_summary['return_qty'][$key] = $g_total_summary['return_qty'][$key] ?? 0;
                                $g_total_summary['return_qty'][$key] += $value['return'];
                                $g_total_summary['sale_qty'][$key] = $g_total_summary['sale_qty'][$key] ?? 0;
                                $g_total_summary['sale_qty'][$key] += $value['sale'] - $value['discount'] - $value['return'];
                            }
                            ksort($total_qty);
                            ksort($total_sale_discount_qty);
                            ksort($total_return_qty);
                            ksort($total_sale_qty);
                        }

                        $g_total_summary['sale_tk'] = $g_total_summary['sale_tk'] ?? 0;
                        $g_total_summary['sale_tk'] += $total_sales;
                        $g_total_summary['return_tk'] = $g_total_summary['return_tk'] ?? 0;
                        $g_total_summary['return_tk'] += $total_return;
                        $g_total_summary['dis_tk'] = $g_total_summary['dis_tk'] ?? 0;
                        $g_total_summary['dis_tk'] += $total_discount;
                        $g_total_summary['vat'] = $g_total_summary['vat'] ?? 0;
                        $g_total_summary['vat'] += $total_vat;
                        $g_total_summary['carring'] = $g_total_summary['carring'] ?? 0;
                        $g_total_summary['carring'] += $total_carring;
                        $g_total_summary['others'] = $g_total_summary['others'] ?? 0;
                        $g_total_summary['others'] += $total_others;
                        $g_total_summary['total_tk'] = $g_total_summary['total_tk'] ?? 0;
                        $g_total_summary['total_tk'] += $total_tk;
                        $g_total_summary['prev_due_tk'] = $g_total_summary['prev_due_tk'] ?? 0;
                        $g_total_summary['prev_due_tk'] += $prev_due;
                        $g_total_summary['total_collection_tk'] = $g_total_summary['total_collection_tk'] ?? 0;
                        $g_total_summary['total_collection_tk'] += $total_collection;
                        $g_total_summary['balance'] = $g_total_summary['balance'] ?? 0;
                        $g_total_summary['balance'] += $balance;
                    @endphp
                    <tr>
                        @if (in_array('id', $visible_cols))
                            <td>{{$loop->iteration}}</td>
                        @endif
                        @if (in_array('customer_name', $visible_cols))
                            <td class="text-left text-nowrap">{{ $customer->name ?? '' }}</td>
                        @endif
                        @if (in_array('address', $visible_cols))
                            <td class="text-left text-nowrap">{{$customer->address}}</td>
                        @endif
                        @if (in_array('phone', $visible_cols))
                            <td class="text-left text-nowrap">{{ $customer->mobile ?? $customer->phone ?? '' }}</td>
                        @endif
                        @if (in_array('ledger', $visible_cols))
                            <td>{{$customer->ledger_page}}</td>
                        @endif
                        @if (in_array('price_group', $visible_cols))
                            <td>{{ $customer->priceGroup->name ?? '' }}</td>
                        @endif
                        @if (in_array('type', $visible_cols))
                            <td>{{ $customer->type ?? '' }}</td>
                        @endif
                        @if (in_array('credit_limit', $visible_cols))
                            <td class="text-right">{{$customer->credit_limit ? number_format($customer->credit_limit) . '/=' : ''}}</td>
                        @endif
                        @if (in_array('quantity', $visible_cols))
                            <td class="text-wrap">
                                @foreach ($total_qty as $key => $value)
                                    @if($value > 0)
                                        <div>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</div>
                                    @endif
                                @endforeach
                            </td>
                        @endif
                        @if (in_array('discount_quantity', $visible_cols))
                            <td class="text-wrap">
                                @foreach ($total_sale_discount_qty as $key => $value)
                                    @if($value > 0)
                                        <div>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</div>
                                    @endif
                                @endforeach
                            </td>
                        @endif
                        @if (in_array('return_quantity', $visible_cols))
                            <td class="text-wrap">
                                @foreach ($total_return_qty as $key => $value)
                                    @if($value > 0)
                                        <div>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</div>
                                    @endif
                                @endforeach
                            </td>
                        @endif
                        @if (in_array('sale_quantity', $visible_cols))
                            <td class="text-wrap">
                                @foreach ($total_sale_qty as $key => $value)
                                    @if($value > 0)
                                        <div>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</div>
                                    @endif
                                @endforeach
                            </td>
                        @endif
                        @if (in_array('weight', $visible_cols))
                            <td class="text-nowrap">{{$totalWeight ? $totalWeight / 1000 . ' MT' : ''}}</td>
                        @endif

                        @if (in_array('sale_amount', $visible_cols))
                            <td class="text-right">{{ $total_sales ? number_format($total_sales) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('return_amount', $visible_cols))
                            <td class="text-right">{{ $total_return ? number_format($total_return) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('discount_amount', $visible_cols))
                            <td class="text-right">{{ $total_discount ? number_format($total_discount) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('vat', $visible_cols))
                            <td class="text-right">{{ $total_vat ? number_format($total_vat) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('carring', $visible_cols))
                            <td class="text-right">{{ $total_carring ? number_format($total_carring) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('others', $visible_cols))
                            <td class="text-right">{{ $total_others ? number_format($total_others) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('total_amount', $visible_cols))
                            <td class="text-right">{{ $total_tk ? number_format($total_tk) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('old_due', $visible_cols))
                            <td class="text-right">{{ $prev_due ? number_format($prev_due) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('collection', $visible_cols))
                            <td class="text-right">{{ $total_collection ? number_format($total_collection) . '/=' : '' }}</td>
                        @endif
                        @if (in_array('balance', $visible_cols))
                            <td class="text-right">{{ $balance ? number_format($balance) . '/=' : '' }}</td>
                        @endif

                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    @if (in_array('id', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('customer_name', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('address', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('phone', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('ledger', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('price_group', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('type', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('credit_limit', $visible_cols))
                        <td></td>
                    @endif
                    @if (in_array('quantity', $visible_cols))
                        <td class="text-wrap">
                            @if (isset($g_total_summary['qty']) && $g_total_summary['qty'] > 0)
                                @foreach ($g_total_summary['qty'] as $type => $qty)
                                    <div><strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong></div>
                                @endforeach
                            @endif
                        </td>
                    @endif
                    @if (in_array('discount_quantity', $visible_cols))
                        <td class="text-wrap">
                            @if (isset($g_total_summary['dis_qty']) && $g_total_summary['dis_qty'] > 0)
                                @foreach ($g_total_summary['dis_qty'] as $type => $qty)
                                    <div><strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong></div>
                                @endforeach
                            @endif
                        </td>
                    @endif
                    @if (in_array('return_quantity', $visible_cols))
                        <td class="text-wrap">
                            @if (isset($g_total_summary['return_qty']) && $g_total_summary['return_qty'] > 0)
                                @foreach ($g_total_summary['return_qty'] as $type => $qty)
                                    <div><strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong></div>
                                @endforeach
                            @endif
                        </td>
                    @endif
                    @if (in_array('sale_quantity', $visible_cols))
                        <td class="text-wrap">
                            @if (isset($g_total_summary['sale_qty']) && $g_total_summary['sale_qty'] > 0)
                                @foreach ($g_total_summary['sale_qty'] as $type => $qty)
                                    <div><strong>{{$qty > 0 ? number_format($qty) . ' ' . trans_choice('labels.' . $type, $qty) : ''}}</strong></div>
                                @endforeach
                            @endif
                        </td>
                    @endif
                    @if (in_array('sale_amount', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['sale_tk']) && $g_total_summary['sale_tk'] > 0 ? number_format($g_total_summary['sale_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('return_amount', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['return_tk']) && $g_total_summary['return_tk'] > 0 ? number_format($g_total_summary['return_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('discount_amount', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['dis_tk']) && $g_total_summary['dis_tk'] > 0 ? number_format($g_total_summary['dis_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('vat', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['vat']) && $g_total_summary['vat'] > 0 ? number_format($g_total_summary['vat']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('carring', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['carring']) && $g_total_summary['carring'] > 0 ? number_format($g_total_summary['carring']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('others', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['others']) && $g_total_summary['others'] > 0 ? number_format($g_total_summary['others']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('total_amount', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['total_tk']) && $g_total_summary['total_tk'] > 0 ? number_format($g_total_summary['total_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('old_due', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['prev_due_tk']) && $g_total_summary['prev_due_tk'] > 0 ? number_format($g_total_summary['prev_due_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('collection', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['total_collection_tk']) && $g_total_summary['total_collection_tk'] > 0 ? number_format($g_total_summary['total_collection_tk']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                    @if (in_array('balance', $visible_cols))
                        <td class="text-right">
                            <strong>{{ isset($g_total_summary['balance']) && $g_total_summary['balance'] > 0 ? number_format($g_total_summary['balance']) . '/=' : '' }}</strong>
                        </td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
