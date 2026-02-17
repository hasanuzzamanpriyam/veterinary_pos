<!-- resources/views/invoice.blade.php -->
@extends('layouts.download')  <!-- Extending the print layout -->

@section('page-title')
    @if ($type == 'due')
        Due Supplier List
    @else
        Supplier List
    @endif
@endsection

@section('main-content')
    @if ($type == 'due')
        <h2 class="mt-4">Due Supplier List</h2>
    @else
        <h2 class="mt-4">All Supplier List</h2>
    @endif

    @php
        $g_total_sumarry = [];
    @endphp
    <table class="printable table table-bordered table-striped mb-0 w-100" cellspacing="0">
        <thead>
        <tr>
            @foreach ( $columns as $key => $column )
                <th class="{{$key}}">{{ $column }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                @php
                    $total_purchases = $supplier->total_purchases ?? 0;
                    $g_total_sumarry['purchases'] = $g_total_sumarry['purchases'] ?? 0;
                    $g_total_sumarry['purchases'] += $total_purchases;
                    $total_return = $supplier->total_returns ?? 0;
                    $g_total_sumarry['return'] = $g_total_sumarry['return'] ?? 0;
                    $g_total_sumarry['return'] += $total_return;
                    $total_discount = $supplier->total_price_discounts ?? 0;
                    $g_total_sumarry['discount'] = $g_total_sumarry['discount'] ?? 0;
                    $g_total_sumarry['discount'] += $total_discount;
                    $total_carring = $supplier->total_carring ?? 0;
                    $g_total_sumarry['carring'] = $g_total_sumarry['carring'] ?? 0;
                    $g_total_sumarry['carring'] += $total_carring;
                    $total_vat = $supplier->total_vat ?? 0;
                    $g_total_sumarry['vat'] = $g_total_sumarry['vat'] ?? 0;
                    $g_total_sumarry['vat'] += $total_vat;
                    $total_others = $supplier->total_others ?? 0;
                    $g_total_sumarry['others'] = $g_total_sumarry['others'] ?? 0;
                    $g_total_sumarry['others'] += $total_others;
                    $total_payments = $supplier->total_payments ?? 0;
                    $g_total_sumarry['payment'] = $g_total_sumarry['payment'] ?? 0;
                    $g_total_sumarry['payment'] += $total_payments;
                    $prev_due = $supplier->previous_due ?? 0;
                    $g_total_sumarry['previous_due'] = $g_total_sumarry['previous_due'] ?? 0;
                    $g_total_sumarry['previous_due'] += $prev_due;

                    $balance = $supplier->balance;
                    $g_total_sumarry['balance'] = $g_total_sumarry['balance'] ?? 0;
                    $g_total_sumarry['balance'] += $balance;



                    $total_qty = [];
                    $total_purchase_discount_qty = [];
                    $total_return_qty = [];
                    $total_purchase_qty = [];
                    $total_tk = $total_purchases - $total_return - $total_discount;
                    $total_G_payment = $total_payments + $total_vat + $total_carring + $total_others;
                    $g_total_sumarry['total_tk'] = $g_total_sumarry['total_tk'] ?? 0;
                    $g_total_sumarry['total_tk'] += $total_purchases;
                    $g_total_sumarry['total_g_tk'] = $g_total_sumarry['total_g_tk'] ?? 0;
                    $g_total_sumarry['total_g_tk'] += $total_G_payment;

                    $totalWeight = $supplierWeights->firstWhere('supplier_id', $supplier->id)->net_weight ?? 0;
                    $g_total_sumarry['weight'] = $g_total_sumarry['weight'] ?? 0;
                    $g_total_sumarry['weight'] += $totalWeight;

                    $qty_summary = $group_by_supplier->get($supplier->id);
                    if (is_array($qty_summary) && count($qty_summary) > 0) {
                        foreach($qty_summary as $key => $value) {
                            $total_qty[$key] = $total_qty[$key] ?? 0;
                            $total_purchase_discount_qty[$key] = $total_purchase_discount_qty[$key] ?? 0;
                            $total_return_qty[$key] = $total_return_qty[$key] ?? 0;
                            $total_purchase_qty[$key] = $total_purchase_qty[$key] ?? 0;
                            $g_total_sumarry['purchase_qty'][$key] = $g_total_sumarry['purchase_qty'][$key] ?? 0;
                            $g_total_sumarry['purchase_qty'][$key] += $value['purchase'];
                            $g_total_sumarry['discount_qty'][$key] = $g_total_sumarry['discount_qty'][$key] ?? 0;
                            $g_total_sumarry['discount_qty'][$key] += $value['discount'];
                            $g_total_sumarry['return_qty'][$key] = $g_total_sumarry['return_qty'][$key] ?? 0;
                            $g_total_sumarry['return_qty'][$key] += $value['return'];
                            $g_total_sumarry['total_qty'][$key] = $g_total_sumarry['total_qty'][$key] ?? 0;
                            $g_total_sumarry['total_qty'][$key] += $value['purchase'] - $value['discount'] - $value['return'];

                            $total_qty[$key] += $value['purchase'];
                            $total_purchase_discount_qty[$key] += $value['discount'];
                            $total_return_qty[$key] += $value['return'];
                            $total_purchase_qty[$key] += $value['purchase'] - $value['discount'] - $value['return'];
                        }
                        ksort($total_qty);
                        ksort($total_purchase_discount_qty);
                        ksort($total_return_qty);
                        ksort($total_purchase_qty);
                    }
                @endphp
                <tr>
                    @if ( array_key_exists("id", $columns) )
                    {{-- ID witll be serial --}}
                    <td class="id">{{ $loop->iteration }}</td>
                    @endif
                    @if ( array_key_exists("name", $columns) )
                    <td class="supplier_name_td name">{{ $supplier->company_name }}</td>
                    @endif
                    @if ( array_key_exists("address", $columns) )
                    <td class="supplier_address_td address">{{ $supplier->address }}</td>
                    @endif
                    @if ( array_key_exists("phone", $columns) )
                    <td class="supplier_phone_td phone">{{ $supplier->mobile }}</td>
                    @endif
                    @if ( array_key_exists("ledger", $columns) )
                    <td class="supplier_ledger_td ledger">{{ $supplier->ledger_page }}</td>
                    @endif
                    @if ( array_key_exists("credit_limit", $columns) )
                    {{-- Credit Limit --}}
                    <td class="text-right supplier_credit_limit_td credit_limit">
                        {{ $supplier->credit_limit ? number_format($supplier->credit_limit) . '/=' : '' }}
                    </td>
                    @endif

                    @if ( array_key_exists("quantity", $columns) )
                    <td class="quantity">
                        @foreach ($total_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("discount_qty", $columns) )
                    <td class="discount_qty">
                        @foreach ($total_purchase_discount_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("return_qty", $columns) )
                    <td class="return_qty">
                        @foreach ($total_return_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("purchase_qty", $columns) )
                    <td class="purchase_qty">
                        @foreach ($total_purchase_qty as $key => $value)
                            @if($value > 0)
                                <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                            @endif
                        @endforeach
                    </td>
                    @endif
                    @if ( array_key_exists("weight", $columns) )
                    {{-- Weight --}}
                    <td class="text-right weight">{{ $totalWeight ? $totalWeight / 1000 : '' }}</td>
                    @endif
                    @if ( array_key_exists("purchase_amount", $columns) )
                    {{-- Total purchases/Price --}}
                    <td class="text-right purchase_amount">{{ $total_purchases ? $total_purchases : '' }}</td>
                    @endif

                    @if ( array_key_exists("return", $columns) )
                    {{-- Return --}}
                    <td class="text-right return">{{ $total_return ? $total_return : '' }}</td>
                    @endif
                    @if ( array_key_exists("discount", $columns) )
                    {{-- Discount --}}
                    <td class="text-right discount">{{ $total_discount ? $total_discount : '' }}</td>
                    @endif
                    @if ( array_key_exists("total", $columns) )
                    {{-- Total TK --}}
                    <td class="text-right total">{{ $total_tk ? $total_tk : '' }}</td>
                    @endif
                    @if ( array_key_exists("vat", $columns) )
                    {{-- VAT --}}
                    <td class="text-right vat">{{ $total_vat ? $total_vat : '' }}</td>
                    @endif
                    @if ( array_key_exists("carring", $columns) )
                    {{-- Carring --}}
                    <td class="text-right carring">{{ $total_carring ? $total_carring : '' }}</td>
                    @endif
                    @if ( array_key_exists("others", $columns) )
                    {{-- Others --}}
                    <td class="text-right others">{{ $total_others ? $total_others : '' }}</td>
                    @endif

                    @if ( array_key_exists("payment", $columns) )
                    {{-- payment --}}
                    <td class="text-right payment">{{ $total_payments ? $total_payments : '' }}</td>
                    @endif
                    @if ( array_key_exists("total_payment", $columns) )
                    {{-- payment --}}
                    <td class="text-right total_payment">{{ $total_G_payment ? $total_G_payment : '' }}</td>
                    @endif
                    @if ( array_key_exists("old_due", $columns) )
                    {{-- old_due --}}
                    <td class="text-right old_due">{{ $prev_due ? $prev_due : '' }}</td>
                    @endif
                    @if ( array_key_exists("balance", $columns) )
                    {{-- Balance --}}
                    <td class="text-right balance">{{ $balance ? $balance : '' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                @if ( array_key_exists("id", $columns) )
                <td class="id"></td>
                @endif
                @if ( array_key_exists("name", $columns) )
                <td class="name"></td>
                @endif
                @if ( array_key_exists("address", $columns) )
                <td class="address"></td>
                @endif
                @if ( array_key_exists("phone", $columns) )
                <td class="phone"></td>
                @endif
                @if ( array_key_exists("ledger", $columns) )
                <td class="ledger"></td>
                @endif
                @if ( array_key_exists("credit_limit", $columns) )
                <td class="credit_limit"></td>
                @endif
                @if ( array_key_exists("quantity", $columns) )
                <td class="quantity">
                    @if( isset($g_total_sumarry['purchase_qty']) && count($g_total_sumarry['purchase_qty']) > 0 )
                        @php(ksort($g_total_sumarry['purchase_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['purchase_qty']) && count($g_total_sumarry['purchase_qty']) > 0)
                            @foreach($g_total_sumarry['purchase_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("discount_qty", $columns) )
                <td class="discount_qty">
                    @if( isset($g_total_sumarry['discount_qty']) && count($g_total_sumarry['discount_qty']) > 0 )
                        @php(ksort($g_total_sumarry['discount_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['discount_qty']) && count($g_total_sumarry['discount_qty']) > 0)
                            @foreach($g_total_sumarry['discount_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("return_qty", $columns) )
                <td class="return_qty">
                    @if( isset($g_total_sumarry['return_qty']) && count($g_total_sumarry['return_qty']) > 0 )
                        @php(ksort($g_total_sumarry['return_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['return_qty']) && count($g_total_sumarry['return_qty']) > 0)
                            @foreach($g_total_sumarry['return_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("purchase_qty", $columns) )
                <td class="purchase_qty">
                    @if( isset($g_total_sumarry['total_qty']) && count($g_total_sumarry['total_qty']) > 0 )
                        @php(ksort($g_total_sumarry['total_qty']))
                    @endif
                    <strong>
                        @if(isset($g_total_sumarry['total_qty']) && count($g_total_sumarry['total_qty']) > 0)
                            @foreach($g_total_sumarry['total_qty'] as $key => $value)
                                @if($value > 0)
                                    <span>{{ number_format($value) }} {{ trans_choice('labels.'.$key, $value) }}</span>
                                @endif
                            @endforeach
                        @endif
                    </strong>
                </td>
                @endif
                @if ( array_key_exists("weight", $columns) )
                <td><b>{{$g_total_sumarry['weight'] ? $g_total_sumarry['weight'] / 1000 : ''}}</b></td>
                @endif
                @if ( array_key_exists("purchase_amount", $columns) )
                <td class="text-right purchase_amount" style="text-align: right"><b>{{$g_total_sumarry['purchases'] ? number_format($g_total_sumarry['purchases']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("return", $columns) )
                <td class="text-right return" style="text-align: right"><b>{{$g_total_sumarry['return'] ? number_format($g_total_sumarry['return']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("discount", $columns) )
                <td class="text-right discount" style="text-align: right"><b>{{$g_total_sumarry['discount'] ? number_format($g_total_sumarry['discount']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("total", $columns) )
                <td class="text-right total" style="text-align: right"><b>{{$g_total_sumarry['total_tk'] ? number_format($g_total_sumarry['total_tk']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("vat", $columns) )
                <td class="text-right carring" style="text-align: right"><b>{{$g_total_sumarry['vat'] ? number_format($g_total_sumarry['vat']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("carring", $columns) )
                <td class="text-right carring" style="text-align: right"><b>{{$g_total_sumarry['carring'] ? number_format($g_total_sumarry['carring']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("others", $columns) )
                <td class="text-right others" style="text-align: right"><b>{{$g_total_sumarry['others'] ? number_format($g_total_sumarry['others']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("payment", $columns) )
                <td class="text-right payment" style="text-align: right"><b>{{$g_total_sumarry['payment'] ? number_format($g_total_sumarry['payment']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("total_payment", $columns) )
                <td class="text-right total_payment" style="text-align: right"><b>{{$g_total_sumarry['total_g_tk'] ? number_format($g_total_sumarry['total_g_tk']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("old_due", $columns) )
                <td class="text-right old_due"><b>{{$g_total_sumarry['previous_due'] ? number_format($g_total_sumarry['previous_due']) . '/=' : ''}}</b></td>
                @endif
                @if ( array_key_exists("balance", $columns) )
                <td class="text-right balance"><b>{{$g_total_sumarry['balance'] ? number_format($g_total_sumarry['balance']) . '/=' : ''}}</b></td>
                @endif
            </tr>
        </tfoot>
    </table>
@endsection

